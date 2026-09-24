<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $enquiry
     */
    public function __construct(
        public array $enquiry,
        public ?int $spamScore = null,
        public ?string $spamStatus = null,
        public ?int $enquiryId = null,
    ) {
    }

    public function envelope(): Envelope
    {
        $prefix = $this->spamStatus === 'suspicious' ? "[CHECK: {$this->spamScore}] " : '';

        return new Envelope(
            replyTo: [new Address($this->enquiry['email'], $this->enquiry['name']),],
            subject: $prefix.'Website enquiry: '.$this->enquiry['service'],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.enquiry');
    }

    /**
     * @return array<int, mixed>
     */
    public function attachments(): array
    {
        return [];
    }
}
