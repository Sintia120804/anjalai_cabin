<?php

use App\Http\Controllers\Admin\WahanaController;
use App\Http\Controllers\Admin\TentangController;
use App\Http\Controllers\Admin\GaleriUmumController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingManualController;
use App\Http\Controllers\Admin\CabinController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicCabinController;
use App\Http\Controllers\UserBookingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route Auth
Auth::routes();
// Google Auth
Route::get('/auth/google/redirect', [\App\Http\Controllers\SocialiteController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [\App\Http\Controllers\SocialiteController::class, 'callback'])->name('google.callback');


// Landing Page & Detail
Route::get('/', [PublicCabinController::class, 'index'])->name('welcome');
Route::get('/cabin/{cabin}', [PublicCabinController::class, 'show'])->name('cabin.show');

// Admin Routes
Route::prefix('admin')
     ->middleware(['auth', 'admin'])
     ->group(function () {

          Route::get('/dashboard', [DashboardController::class, 'index'])
               ->name('admin.dashboard');

          Route::resource('/cabin', CabinController::class)
               ->names('admin.cabin');

          Route::resource('/booking', BookingController::class)
               ->names('admin.booking');
          Route::patch('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.updateStatus');

          Route::resource('/booking-manual', BookingManualController::class)
               ->names('admin.booking_manual');

          Route::resource('/fasilitas-tambahan', \App\Http\Controllers\Admin\FasilitasTambahanController::class)
               ->names('admin.fasilitas_tambahan');

          Route::resource('/wahana', WahanaController::class)
               ->names('admin.wahana');

          Route::resource('/tentang', TentangController::class)
               ->names('admin.tentang');

          Route::resource('/galeri-umum', GaleriUmumController::class)
               ->names('admin.galeri_umum');

     });

// User/Pengunjung Routes
Route::middleware(['auth'])->group(function () {
     Route::get('/dashboard', [UserBookingController::class, 'index'])->name('user.dashboard');
     Route::post('/cabin/{cabin}/book', [UserBookingController::class, 'store'])->name('user.booking.store');
     Route::delete('/booking/{booking}', [UserBookingController::class, 'destroy'])->name('user.booking.destroy');

     // Manual Payment Upload
     Route::post('/payment/upload/{booking}', [PaymentController::class, 'uploadProof'])->name('payment.upload');

});

