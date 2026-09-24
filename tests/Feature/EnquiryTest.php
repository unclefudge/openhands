<?php

namespace Tests\Feature;

use App\Mail\EnquiryConfirmation;
use App\Mail\EnquiryReceived;
use App\Mail\WeeklyEnquiryReport;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_genuine_enquiry_is_saved_and_emailed(): void
    {
        Mail::fake();

        $response = $this->post(route('enquiry.store'), $this->validEnquiry());

        $response->assertRedirect()->assertSessionHas('enquiry_sent');

        $this->assertDatabaseHas('enquiries', [
            'email' => 'person@example.com',
            'spam_score' => 0,
            'spam_status' => 'delivered',
        ]);

        Mail::assertSent(EnquiryReceived::class, fn (EnquiryReceived $mail): bool => $mail->enquiry['email'] === 'person@example.com');
        Mail::assertSent(EnquiryConfirmation::class, fn (EnquiryConfirmation $mail): bool => $mail->hasTo('person@example.com'));
    }

    public function test_the_honeypot_is_saved_as_blocked_without_sending_mail(): void
    {
        Mail::fake();

        $response = $this->post(route('enquiry.store'), [
            ...$this->validEnquiry(),
            'website' => 'https://spam.example',
        ]);

        $response->assertRedirect()->assertSessionHas('enquiry_sent');
        $this->assertDatabaseHas('enquiries', ['email' => 'person@example.com', 'spam_status' => 'blocked']);
        Mail::assertNothingSent();
    }

    public function test_an_instant_submission_is_quarantined_for_review(): void
    {
        Mail::fake();

        $this->post(route('enquiry.store'), [
            ...$this->validEnquiry(),
            'form_started_at' => Crypt::encryptString((string) time()),
        ])->assertSessionHas('enquiry_sent');

        $enquiry = Enquiry::firstOrFail();
        $this->assertGreaterThanOrEqual(30, $enquiry->spam_score);
        $this->assertSame('suspicious', $enquiry->spam_status);
        Mail::assertSent(EnquiryReceived::class);
        Mail::assertNotSent(EnquiryConfirmation::class);
    }

    public function test_an_seo_sales_pitch_without_a_link_is_quarantined(): void
    {
        Mail::fake();

        $this->post(route('enquiry.store'), [
            ...$this->validEnquiry(),
            'email' => 'salesperson@gmail.com',
            'organisation' => '',
            'message' => 'We provide SEO services focused on search visibility. Shall I send our plans and pricing for review?',
        ])->assertSessionHas('enquiry_sent');

        $enquiry = Enquiry::firstOrFail();
        $this->assertGreaterThanOrEqual(60, $enquiry->spam_score);
        $this->assertContains($enquiry->spam_status, ['quarantined', 'blocked']);
        Mail::assertNothingSent();
    }

    public function test_an_overseas_country_is_only_a_supporting_signal(): void
    {
        Mail::fake();

        $this->withHeader('CF-IPCountry', 'US')
            ->post(route('enquiry.store'), $this->validEnquiry())
            ->assertSessionHas('enquiry_sent');

        $enquiry = Enquiry::firstOrFail();
        $this->assertSame('US', $enquiry->ip_country);
        $this->assertSame(5, $enquiry->spam_score);
        $this->assertSame('delivered', $enquiry->spam_status);
    }

    public function test_an_admin_can_mark_a_quarantined_enquiry_as_genuine(): void
    {
        Mail::fake();
        $user = User::create(['name' => 'Fudge', 'email' => 'admin@example.com', 'password' => 'a-secure-test-password']);

        $this->post(route('enquiry.store'), [
            ...$this->validEnquiry(),
            'email' => 'salesperson@gmail.com',
            'organisation' => '',
            'message' => 'We provide SEO services focused on search visibility. Shall I send our plans and pricing for review?',
        ]);

        Mail::fake();
        $enquiry = Enquiry::firstOrFail();

        $this->actingAs($user)
            ->post(route('admin.enquiries.genuine', $enquiry))
            ->assertSessionHas('status');

        $this->assertSame('genuine', $enquiry->fresh()->review_status);
        Mail::assertSent(EnquiryReceived::class);
        Mail::assertNotSent(EnquiryConfirmation::class);
    }

    public function test_the_weekly_report_can_be_sent(): void
    {
        Mail::fake();

        $this->post(route('enquiry.store'), $this->validEnquiry());
        $this->artisan('enquiries:weekly-report')->assertSuccessful();

        Mail::assertSent(WeeklyEnquiryReport::class);
    }

    /** @return array<string, string> */
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
