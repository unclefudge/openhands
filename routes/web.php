<?php

use App\Http\Controllers\EnquiryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/work/safeworksite', 'work.safeworksite')->name('work.safeworksite');
Route::view('/work/c3-booking', 'work.c3-booking')->name('work.c3-booking');
Route::view('/work/clientbill', 'work.clientbill')->name('work.clientbill');
Route::post('/enquiry', EnquiryController::class)->middleware('throttle:3,60')->name('enquiry.store');
