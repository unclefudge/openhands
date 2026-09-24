<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnquiryRequest;
use App\Mail\EnquiryConfirmation;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Services\EnquirySpamScorer;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class EnquiryController extends Controller
{
    public function __invoke(EnquiryRequest $request, EnquirySpamScorer $scorer): RedirectResponse
    {
        $honeypotCompleted = $request->filled('website');
        $turnstilePassed = $honeypotCompleted ? null : $this->verifyTurnstileWhenConfigured($request);
        $completionSeconds = $this->completionSeconds($request->string('form_started_at')->toString());
        $ipCountry = $this->cloudflareCountry($request->header('CF-IPCountry'));

        $enquiryData = $request->safe()->except([
            'website',
            'form_started_at',
            'cf-turnstile-response',
            'genuine',
        ]);

        $assessment = $scorer->assess([
            ...$enquiryData,
            'website' => $request->input('website'),
            'completion_seconds' => $completionSeconds,
            'ip_country' => $ipCountry,
        ]);

        $enquiry = Enquiry::create([
            ...$enquiryData,
            'spam_score' => $assessment['score'],
            'original_spam_score' => $assessment['score'],
            'spam_status' => $assessment['status'],
            'score_reasons' => $assessment['reasons'],
            'ip_hash' => $this->hashIp($this->clientIp($request)),
            'ip_country' => $ipCountry,
            'email_country' => $assessment['email_country'],
            'link_countries' => $assessment['link_countries'],
            'message_fingerprint' => $assessment['fingerprint'],
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
            'turnstile_passed' => $turnstilePassed,
            'completion_seconds' => $completionSeconds,
            'last_scored_at' => now(),
        ]);

        if (in_array($enquiry->spam_status, ['delivered', 'suspicious'], true)) {
            $this->sendOwnerNotification($enquiry);
        }

        if ($enquiry->spam_status === 'delivered') {
            $this->sendConfirmation($enquiry);
        }

        // Every valid submission receives the same response so blocked senders
        // cannot use the site to tune their spam around the scoring rules.
        return back()->with('enquiry_sent', $this->successMessage());
    }

    private function completionSeconds(string $encryptedStartedAt): ?int
    {
        try {
            $startedAt = (int) Crypt::decryptString($encryptedStartedAt);
            $elapsed = time() - $startedAt;

            return $elapsed >= 0 ? $elapsed : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function verifyTurnstileWhenConfigured(EnquiryRequest $request): ?bool
    {
        $secret = config('services.turnstile.secret_key');

        if (blank($secret)) {
            return null;
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

        $expectedHostnames = array_filter([
            parse_url((string) config('app.url'), PHP_URL_HOST),
            'openhands.com.au',
            'www.openhands.com.au',
        ]);

        if (
            ! $response->successful()
            || ! $response->json('success')
            || ! in_array($response->json('hostname'), $expectedHostnames, true)
            || $response->json('action') !== 'enquiry'
        ) {
            throw ValidationException::withMessages([
                'turnstile' => 'Please complete the spam check and try again.',
            ]);
        }

        return true;
    }

    private function cloudflareCountry(?string $country): ?string
    {
        $country = strtoupper(trim((string) $country));

        return preg_match('/^[A-Z]{2}$/', $country) && ! in_array($country, ['XX', 'T1'], true)
            ? $country
            : null;
    }

    private function hashIp(?string $ip): ?string
    {
        return $ip
            ? hash_hmac('sha256', $ip, (string) config('app.key'))
            : null;
    }

    private function clientIp(EnquiryRequest $request): ?string
    {
        return (string) ($request->hasHeader('CF-Ray')
            ? $request->header('CF-Connecting-IP', $request->ip())
            : $request->ip());
    }

    private function sendOwnerNotification(Enquiry $enquiry): void
    {
        try {
            Mail::to(
                config('mail.enquiry_to.address'),
                config('mail.enquiry_to.name'),
            )->send(new EnquiryReceived(
                enquiry: $enquiry->mailPayload(),
                spamScore: $enquiry->spam_score,
                spamStatus: $enquiry->spam_status,
                enquiryId: $enquiry->id,
            ));

            $enquiry->update(['notification_sent_at' => now()]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function sendConfirmation(Enquiry $enquiry): void
    {
        try {
            Mail::to($enquiry->email, $enquiry->name)
                ->send(new EnquiryConfirmation($enquiry->mailPayload()));

            $enquiry->update(['confirmation_sent_at' => now()]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function successMessage(): string
    {
        return 'Thanks—your enquiry has been sent. I’ll be in touch personally.';
    }
}
