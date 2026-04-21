<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pembayaran;

$payments = Pembayaran::latest()->take(10)->get();
foreach ($payments as $p) {
    echo "ID: {$p->id}, Booking ID: {$p->booking_id}, Method: {$p->metode_pembayaran}, Proof: {$p->bukti_pembayaran}, Status: {$p->status_pembayaran}\n";
}
