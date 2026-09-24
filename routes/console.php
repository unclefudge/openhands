<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment('Build useful software with care.');
})->purpose('Display a short message');

Schedule::command('enquiries:weekly-report')
    ->mondays()
    ->at('08:00')
    ->timezone('Australia/Hobart')
    ->withoutOverlapping();
