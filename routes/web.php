<?php

use App\Http\Controllers\Admin\WahanaController;
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
Route::get('/cabin/{cabin}/available-units', [PublicCabinController::class, 'availableUnits'])->name('cabin.available_units');
Route::get('/wahana/{wahana}', [PublicCabinController::class, 'showWahana'])->name('wahana.show');

// Admin Routes
Route::prefix('admin')
     ->middleware(['auth', 'admin'])
     ->group(function () {

          Route::get('/dashboard', [DashboardController::class, 'index'])
               ->name('admin.dashboard');

          Route::resource('/cabin', CabinController::class)
               ->names('admin.cabin');

          Route::get('/cabin/{cabin}/units', [\App\Http\Controllers\Admin\CabinUnitController::class, 'index'])->name('admin.cabin.units.index');
          Route::post('/cabin/{cabin}/units', [\App\Http\Controllers\Admin\CabinUnitController::class, 'store'])->name('admin.cabin.units.store');
          Route::delete('/cabin-units/{unit}', [\App\Http\Controllers\Admin\CabinUnitController::class, 'destroy'])->name('admin.cabin.units.destroy');


          Route::resource('/booking', BookingController::class)
               ->names('admin.booking');
          Route::patch('/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.updateStatus');

          Route::resource('/booking-manual', BookingManualController::class)
               ->names('admin.booking_manual');



          Route::resource('/wahana', WahanaController::class)
               ->names('admin.wahana');

          Route::resource('/galeri-umum', GaleriUmumController::class)
               ->names('admin.galeri_umum');

     });

// User/Pengunjung Routes
Route::middleware(['auth'])->group(function () {
     Route::get('/dashboard', [UserBookingController::class, 'index'])->name('user.dashboard');
     
     // Cart Routes
     Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
     Route::post('/cart/add/{cabin}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
     Route::delete('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
     Route::patch('/cart/update/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
     Route::post('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

     Route::delete('/booking/{booking}', [UserBookingController::class, 'destroy'])->name('user.booking.destroy');
     Route::get('/booking/{orderId}/pdf', [UserBookingController::class, 'exportPdf'])->name('user.booking.pdf');

     // Manual Payment Upload
     Route::post('/payment/upload/{booking}', [PaymentController::class, 'uploadProof'])->name('payment.upload');

     // Midtrans AJAX Snap Token
     Route::get('/payment/snap-token/{orderId}', [PaymentController::class, 'getSnapToken'])->name('payment.snap_token');
});

// Midtrans Webhook Callback (Exempt from CSRF in bootstrap/app.php)
Route::post('/midtrans/callback', [PaymentController::class, 'handleCallback'])->name('midtrans.callback');
Route::post('/midtrans/frontend-success', [PaymentController::class, 'frontendSuccess'])->name('midtrans.frontend_success');

