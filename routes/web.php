<?php

use App\Http\Controllers\EnquiryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::post('/enquiry', EnquiryController::class)
    ->middleware('throttle:enquiries')
    ->name('enquiry.store');
