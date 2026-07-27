<?php

namespace Tests\Feature;

use App\Mail\EnquiryConfirmation;
use App\Mail\EnquiryReceived;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    public function test_a_genuine_enquiry_is_emailed(): void
    {
        Mail::fake();

        $response = $this->post(route('enquiry.store'), $this->validEnquiry());

        $response
            ->assertRedirect()
            ->assertSessionHas('enquiry_sent');

        Mail::assertSent(EnquiryReceived::class, function (EnquiryReceived $mail): bool {
            return $mail->enquiry['email'] === 'person@example.com';
        });

        Mail::assertSent(EnquiryConfirmation::class, function (EnquiryConfirmation $mail): bool {
            return $mail->hasTo('person@example.com')
                && $mail->enquiry['name'] === 'Genuine Person';
        });
    }

    public function test_the_honeypot_silently_discards_a_bot_submission(): void
    {
        Mail::fake();

        $response = $this->post(route('enquiry.store'), [
            ...$this->validEnquiry(),
            'website' => 'https://spam.example',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('enquiry_sent');

        Mail::assertNothingSent();
    }

    public function test_an_instant_submission_is_rejected(): void
    {
        Mail::fake();

        $response = $this->post(route('enquiry.store'), [
            ...$this->validEnquiry(),
            'form_started_at' => Crypt::encryptString((string) time()),
        ]);

        $response->assertSessionHasErrors('message');
        Mail::assertNothingSent();
    }

    /**
     * @return array<string, string>
     */
    private function validEnquiry(): array
    {
        return [
            'name' => 'Genuine Person',
            'email' => 'person@example.com',
            'organisation' => 'Example Business',
            'phone' => '0400 000 000',
            'service' => 'A custom web application',
            'timeframe' => 'Within 1–3 months',
            'referral' => 'Personal referral',
            'message' => 'We need a practical booking system for our growing organisation.',
            'genuine' => 'yes',
            'website' => '',
            'form_started_at' => Crypt::encryptString((string) (time() - 10)),
        ];
    }
}
