<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_is_available(): void
    {
        $response = $this->withoutVite()->get('/');

        $response
            ->assertOk()
            ->assertSee('Useful software, built with care and built to last.')
            ->assertSee('20+ years')
            ->assertSee('Start a conversation');
    }
}
