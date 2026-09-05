<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_is_available(): void
    {
        $response = $this->withoutVite()->get('/');

        $response
            ->assertOk()
            ->assertSee('Practical web development. Thoughtfully built.')
            ->assertSee('20+ years')
            ->assertSee('Selected work')
            ->assertSee('SafeWorksite')
            ->assertSee('C3 Booking')
            ->assertSee('ClientBill')
            ->assertSee('Start a conversation');
    }

    #[DataProvider('caseStudies')]
    public function test_case_study_pages_are_available(string $uri, string $title): void
    {
        $this->withoutVite()->get($uri)
            ->assertOk()
            ->assertSee($title)
            ->assertSee('Built, deployed and cared for independently.');
    }

    public static function caseStudies(): array
    {
        return [
            ['/work/safeworksite', 'SafeWorksite'],
            ['/work/c3-booking', 'C3 Booking'],
            ['/work/clientbill', 'ClientBill'],
        ];
    }
}
