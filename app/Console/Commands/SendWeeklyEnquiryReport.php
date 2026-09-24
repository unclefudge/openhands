<?php

namespace App\Console\Commands;

use App\Mail\WeeklyEnquiryReport;
use App\Models\Enquiry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyEnquiryReport extends Command
{
    protected $signature = 'enquiries:weekly-report {--days=7}';

    protected $description = 'Email a summary of website enquiries and spam decisions';

    public function handle(): int
    {
        $days = max(1, min(31, (int) $this->option('days')));
        $from = now()->subDays($days);
        $query = Enquiry::where('created_at', '>=', $from);

        $summary = [
            'days' => $days,
            'from' => $from,
            'to' => now(),
            'total' => (clone $query)->count(),
            'delivered' => (clone $query)->where('spam_status', 'delivered')->count(),
            'suspicious' => (clone $query)->where('spam_status', 'suspicious')->count(),
            'quarantined' => (clone $query)->where('spam_status', 'quarantined')->count(),
            'blocked' => (clone $query)->where('spam_status', 'blocked')->count(),
            'confirmed_genuine' => (clone $query)->where('review_status', 'genuine')->count(),
            'confirmed_spam' => (clone $query)->where('review_status', 'spam')->count(),
            'awaiting_review' => Enquiry::where('review_status', 'unreviewed')->whereIn('spam_status', ['suspicious', 'quarantined', 'blocked'])->count(),
        ];

        $reviewQueue = Enquiry::query()
            ->where('review_status', 'unreviewed')
            ->whereIn('spam_status', ['suspicious', 'quarantined', 'blocked'])
            ->latest()
            ->limit(20)
            ->get();

        Mail::to(
            config('mail.enquiry_to.address'),
            config('mail.enquiry_to.name'),
        )->send(new WeeklyEnquiryReport($summary, $reviewQueue));

        $this->info("Weekly enquiry report sent for the past {$days} days.");

        return self::SUCCESS;
    }
}
