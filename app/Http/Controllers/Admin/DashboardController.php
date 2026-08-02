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

        // 1. Chart Data (Pendapatan Bulanan untuk tahun ini)
        $currentYear = date('Y');
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $onlineOrderIds = Booking::whereYear('tanggal_checkin', $currentYear)
                ->whereMonth('tanggal_checkin', $i)
                ->pluck('order_id')->unique();

            $online = Pembayaran::where('status_pembayaran', 'diterima')
                ->whereIn('order_id', $onlineOrderIds)
                ->sum('jumlah_bayar');
                
            $manual = BookingManual::where('status_booking', '!=', 'cancelled')
                ->whereYear('tanggal_checkin', $currentYear)
                ->whereMonth('tanggal_checkin', $i)
                ->sum('total_harga');
                
            $monthlyRevenue[] = $online + $manual;
        }

        // 2. Status Cabin Realtime Hari Ini
        $now = \Carbon\Carbon::now();
        $today_start = $now->copy()->startOfDay();
        $today_end = $now->copy()->endOfDay();

        $cabinStatuses = Cabin::with('units')->get()->map(function ($cabin) use ($today_start, $today_end) {
            $totalUnits = $cabin->units->count();
            
            $bookedOnlineIds = Booking::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'ditolak')
                ->where('tanggal_checkin', '<', $today_end)
                ->where('tanggal_checkout', '>', $today_start)
                ->whereNotNull('cabin_unit_id')
                ->distinct('cabin_unit_id')
                ->pluck('cabin_unit_id')->toArray();

            $bookedManualIds = BookingManual::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'cancelled')
                ->where('tanggal_checkin', '<', $today_end)
                ->where('tanggal_checkout', '>', $today_start)
                ->whereNotNull('cabin_unit_id')
                ->distinct('cabin_unit_id')
                ->pluck('cabin_unit_id')->toArray();

            $bookedIds = array_unique(array_merge($bookedOnlineIds, $bookedManualIds));
            
            $cabin->total_units = $totalUnits;
            $cabin->booked_units = count($bookedIds);
            $cabin->available_units = max(0, $totalUnits - count($bookedIds));
            
            return $cabin;
        });

        // 3. Pendapatan Bulan Ini vs Bulan Lalu
        $currentMonth = date('m');
        $lastMonth = date('m', strtotime('-1 month'));
        $lastMonthYear = date('Y', strtotime('-1 month'));

        $revenueCurrentMonth = Pembayaran::where('status_pembayaran', 'diterima')
            ->whereIn('order_id', Booking::whereYear('tanggal_checkin', $currentYear)->whereMonth('tanggal_checkin', $currentMonth)->pluck('order_id')->unique())
            ->sum('jumlah_bayar') + 
            BookingManual::where('status_booking', '!=', 'cancelled')
            ->whereYear('tanggal_checkin', $currentYear)
            ->whereMonth('tanggal_checkin', $currentMonth)
            ->sum('total_harga');

        $revenueLastMonth = Pembayaran::where('status_pembayaran', 'diterima')
            ->whereIn('order_id', Booking::whereYear('tanggal_checkin', $lastMonthYear)->whereMonth('tanggal_checkin', $lastMonth)->pluck('order_id')->unique())
            ->sum('jumlah_bayar') + 
            BookingManual::where('status_booking', '!=', 'cancelled')
            ->whereYear('tanggal_checkin', $lastMonthYear)
            ->whereMonth('tanggal_checkin', $lastMonth)
            ->sum('total_harga');

        $revenueGrowth = 0;
        if ($revenueLastMonth > 0) {
            $revenueGrowth = (($revenueCurrentMonth - $revenueLastMonth) / $revenueLastMonth) * 100;
        } else if ($revenueCurrentMonth > 0) {
            $revenueGrowth = 100;
        }

        // 4. Tamu Terbaru Daftar
        $latestUsers = User::where('role', 'pengunjung')->latest()->take(5)->get();

        // 5. Cabin Terpopuler
        $popularCabins = Cabin::withCount(['bookings' => function($query) {
            $query->where('status_booking', '!=', 'ditolak');
        }, 'booking_manuals' => function($query) {
            $query->where('status_booking', '!=', 'cancelled');
        }])->get()->map(function($cabin) {
            $cabin->total_bookings = $cabin->bookings_count + $cabin->booking_manuals_count;
            return $cabin;
        })->sortByDesc('total_bookings')->take(5);

        return view('admin.dashboard', compact(
            'totalBookingOnline',
            'totalBookingManual',
            'totalCabin',
            'totalUser',
            'totalPendapatan',
            'latestBookings',
            'latestManuals',
            'monthlyRevenue',
            'cabinStatuses',
            'revenueCurrentMonth',
            'revenueGrowth',
            'latestUsers',
            'popularCabins'
        ));
    }
}
