<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WeeklyEnquiryReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $summary
     * @param  Collection<int, \App\Models\Enquiry>  $reviewQueue
     */
    public function __construct(
        public array $summary,
        public Collection $reviewQueue,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Open Hands weekly enquiry report');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.weekly-enquiry-report');
    }

    public function attachments(): array
    {
        return [];
    }
}
