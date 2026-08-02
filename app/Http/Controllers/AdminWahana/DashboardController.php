<?php

namespace App\Http\Controllers\AdminWahana;

use App\Http\Controllers\Controller;
use App\Models\Wahana;
use App\Models\BookingManualWahana;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWahana    = Wahana::count();
        $wahanas        = Wahana::latest()->get();
        $totalPendapatan = BookingManualWahana::where('status_booking', '!=', 'cancelled')->sum('total_harga');
        $totalBooking    = BookingManualWahana::count();
        $bookingHariIni  = BookingManualWahana::whereDate('tanggal_kunjungan', Carbon::today())->count();
        $bookingTerbaru  = BookingManualWahana::with('wahana')->latest()->take(5)->get();

        return view('admin_wahana.dashboard', compact(
            'totalWahana',
            'wahanas',
            'totalPendapatan',
            'totalBooking',
            'bookingHariIni',
            'bookingTerbaru'
        ));
    }
}
