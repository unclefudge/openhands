<?php

use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;
use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/work/safeworksite', 'work.safeworksite')->name('work.safeworksite');
Route::view('/work/c3-booking', 'work.c3-booking')->name('work.c3-booking');
Route::view('/work/clientbill', 'work.clientbill')->name('work.clientbill');
Route::post('/enquiry', EnquiryController::class)->middleware('throttle:enquiries')->name('enquiry.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [LoginController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('admin.login.store');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (): void {
    Route::redirect('/', '/admin/enquiries')->name('home');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/enquiries', [AdminEnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/enquiries/{enquiry}', [AdminEnquiryController::class, 'show'])->name('enquiries.show');
    Route::post('/enquiries/{enquiry}/spam', [AdminEnquiryController::class, 'markSpam'])->name('enquiries.spam');
    Route::post('/enquiries/{enquiry}/genuine', [AdminEnquiryController::class, 'markGenuine'])->name('enquiries.genuine');
    Route::post('/enquiries/{enquiry}/recalculate', [AdminEnquiryController::class, 'recalculate'])->name('enquiries.recalculate');
});
