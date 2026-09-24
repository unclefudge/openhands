<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Services\EnquirySpamScorer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class EnquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Enquiry::query()->latest();

        if ($request->filled('status')) {
            $query->where('spam_status', $request->string('status')->toString());
        }

        if ($request->filled('review')) {
            $query->where('review_status', $request->string('review')->toString());
        }

        if ($request->filled('search')) {
            $search = '%'.$request->string('search')->toString().'%';
            $query->where(function ($query) use ($search): void {
                $query
                    ->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('organisation', 'like', $search)
                    ->orWhere('message', 'like', $search);
            });
        }

        $since = now()->subDays(7);

        return view('admin.enquiries.index', [
            'enquiries' => $query->paginate(40)->withQueryString(),
            'summary' => [
                'total' => Enquiry::where('created_at', '>=', $since)->count(),
                'delivered' => Enquiry::where('created_at', '>=', $since)->where('spam_status', 'delivered')->count(),
                'suspicious' => Enquiry::where('created_at', '>=', $since)->where('spam_status', 'suspicious')->count(),
                'quarantined' => Enquiry::where('created_at', '>=', $since)->where('spam_status', 'quarantined')->count(),
                'blocked' => Enquiry::where('created_at', '>=', $since)->where('spam_status', 'blocked')->count(),
                'awaiting_review' => Enquiry::awaitingReview()->whereIn('spam_status', ['suspicious', 'quarantined', 'blocked'])->count(),
            ],
        ]);
    }

    public function show(Enquiry $enquiry): View
    {
        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function markSpam(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $enquiry->update([
            'review_status' => 'spam',
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Marked as spam. Future similar messages will receive additional risk points.');
    }

    public function markGenuine(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $enquiry->update([
            'review_status' => 'genuine',
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        if (! $enquiry->notification_sent_at) {
            try {
                Mail::to(
                    config('mail.enquiry_to.address'),
                    config('mail.enquiry_to.name'),
                )->send(new EnquiryReceived(
                    enquiry: $enquiry->mailPayload(),
                    spamScore: $enquiry->spam_score,
                    spamStatus: 'reviewed genuine',
                    enquiryId: $enquiry->id,
                ));

                $enquiry->update(['notification_sent_at' => now()]);
            } catch (Throwable $exception) {
                report($exception);

                return back()->with('error', 'Marked genuine, but the notification email could not be sent.');
            }
        }

        return back()->with('status', 'Marked as genuine. Any previously withheld owner notification has now been sent.');
    }

    public function recalculate(Enquiry $enquiry, EnquirySpamScorer $scorer): RedirectResponse
    {
        $assessment = $scorer->assess([
            ...$enquiry->mailPayload(),
            'completion_seconds' => $enquiry->completion_seconds,
            'ip_country' => $enquiry->ip_country,
        ]);

        $enquiry->update([
            'spam_score' => $assessment['score'],
            'spam_status' => $assessment['status'],
            'score_reasons' => $assessment['reasons'],
            'email_country' => $assessment['email_country'],
            'link_countries' => $assessment['link_countries'],
            'message_fingerprint' => $assessment['fingerprint'],
            'last_scored_at' => now(),
        ]);

        return back()->with('status', 'Spam score recalculated using the latest rules and review history.');
    }
}
