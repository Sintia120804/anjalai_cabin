<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingManual;
use App\Models\Cabin;
use App\Models\Pembayaran;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookingOnline = Booking::count();
        $totalBookingManual = BookingManual::count();
        $totalCabin         = Cabin::count();
        $totalUser          = User::where('role', 'pengunjung')->count();
        $totalPendapatan    = Pembayaran::where('status_pembayaran', 'diterima')->sum('jumlah_bayar') + BookingManual::sum('total_harga');
        // Get latest bookings
        $latestBookings = Booking::with(['user', 'cabin'])->latest()->take(5)->get();
        $latestManuals = BookingManual::with(['cabin'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBookingOnline',
            'totalBookingManual',
            'totalCabin',
            'totalUser',
            'totalPendapatan',
            'latestBookings',
            'latestManuals'
        ));
    }
}
