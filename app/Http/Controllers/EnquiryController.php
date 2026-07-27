<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnquiryRequest;
use App\Mail\EnquiryConfirmation;
use App\Mail\EnquiryReceived;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class EnquiryController extends Controller
{
    public function __invoke(EnquiryRequest $request): RedirectResponse
    {
        // Honeypot fields are hidden from genuine visitors but often completed
        // automatically by spambots. Return a normal-looking success response
        // without sending anything so the bot learns nothing useful.
        if ($request->filled('website')) {
            return back()->with('enquiry_sent', $this->successMessage());
        }

        $this->ensureHumanCompletionTime($request->string('form_started_at')->toString());
        $this->verifyTurnstileWhenConfigured($request);

        $enquiry = $request->safe()->except([
            'website',
            'form_started_at',
            'cf-turnstile-response',
        ]);

        Mail::to(
            config('mail.enquiry_to.address'),
            config('mail.enquiry_to.name'),
        )->send(new EnquiryReceived($enquiry));

        // The enquiry has already reached Open Hands, so a failure while
        // sending the courtesy confirmation should not invite the visitor
        // to submit the form again and create a duplicate enquiry.
        try {
            Mail::to($enquiry['email'], $enquiry['name'])
                ->send(new EnquiryConfirmation($enquiry));
        } catch (Throwable $exception) {
            report($exception);
        }

        return back()->with('enquiry_sent', $this->successMessage());
    }

    private function ensureHumanCompletionTime(string $encryptedStartedAt): void
    {
        try {
            $startedAt = (int) Crypt::decryptString($encryptedStartedAt);
        } catch (Throwable) {
            $startedAt = 0;
        }

        $elapsed = time() - $startedAt;

        if ($elapsed < 3 || $elapsed > 43_200) {
            throw ValidationException::withMessages([
                'message' => 'Please take a moment to check your enquiry and try again.',
            ]);
        }
    }

    private function verifyTurnstileWhenConfigured(EnquiryRequest $request): void
    {
        $secret = config('services.turnstile.secret_key');

        if (blank($secret)) {
            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $request->input('cf-turnstile-response'),
                    'remoteip' => $request->ip(),
                ]);
        } catch (ConnectionException) {
            throw ValidationException::withMessages([
                'turnstile' => 'The spam check is temporarily unavailable. Please try again shortly.',
            ]);
        }

        if (! $response->successful() || ! $response->json('success')) {
            throw ValidationException::withMessages([
                'turnstile' => 'Please complete the spam check and try again.',
            ]);
        }
    }

    private function successMessage(): string
    {
        return 'Thanks—your enquiry has been sent. I’ll be in touch personally.';
    }
}
