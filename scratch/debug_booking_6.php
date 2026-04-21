<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Booking;

$booking = Booking::with('pembayaran')->find(6);
if ($booking) {
    echo "BOOKING ID: " . $booking->id . "\n";
    echo "STATUS BOOKING: " . $booking->status_booking . "\n";
    if ($booking->pembayaran) {
        echo "PAYMENT ID: " . $booking->pembayaran->id . "\n";
        echo "METODE: " . ($booking->pembayaran->metode_pembayaran ?? 'NULL') . "\n";
        echo "BUKTI: " . ($booking->pembayaran->bukti_pembayaran ?? 'NULL') . "\n";
        echo "STATUS PAYMENT: " . $booking->pembayaran->status_pembayaran . "\n";
    } else {
        echo "NO PAYMENT RECORD FOUND\n";
    }
} else {
    echo "BOOKING NOT FOUND\n";
}
