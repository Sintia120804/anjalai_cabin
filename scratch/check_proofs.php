<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pembayaran;

$payments = Pembayaran::whereNotNull('bukti_pembayaran')->get();
foreach ($payments as $p) {
    echo "ID: {$p->id}, Booking ID: {$p->booking_id}, Proof: {$p->bukti_pembayaran}\n";
}
