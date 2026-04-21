<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pembayaran;

$pembayaran = Pembayaran::where('booking_id', 6)->first();
if ($pembayaran) {
    // Pick an existing file from disk to simulate
    $pembayaran->bukti_pembayaran = 'bukti_pembayaran/bukti_1775787588_2.jpg';
    $pembayaran->metode_pembayaran = 'Transfer Bank';
    $pembayaran->save();
    echo "Simulated proof for Booking #6 SUCCESS\n";
} else {
    echo "Payment record for Booking #6 NOT FOUND\n";
}
