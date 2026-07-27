<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnquiryConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $enquiry
     */
    public function __construct(public array $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address(
                    config('mail.enquiry_to.address'),
                    config('mail.enquiry_to.name'),
                ),
            ],
            subject: 'Thanks—your Open Hands enquiry has been received',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.enquiry-confirmation');
    }

    /**
     * @return array<int, mixed>
     */
    public function attachments(): array
    {
        return [];
    }
}
