<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoCancelBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:auto-cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batalkan pesanan yang belum dibayar lebih dari 2 jam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = now()->subHours(2);

        $bookings = \App\Models\Booking::where('status_booking', 'pending')
            ->where('created_at', '<', $limit)
            ->get();

        $count = $bookings->count();

        foreach ($bookings as $booking) {
            $booking->update(['status_booking' => 'ditolak']);
            
            if ($booking->pembayaran) {
                $booking->pembayaran->update(['status_pembayaran' => 'ditolak']);
            }
            
            $this->info("Booking ID {$booking->id} telah dibatalkan otomatis.");
        }

        $this->info("Total {$count} pesanan telah dibatalkan otomatis.");
    }
}
