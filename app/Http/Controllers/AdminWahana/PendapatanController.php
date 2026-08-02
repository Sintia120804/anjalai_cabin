<?php

namespace App\Http\Controllers\AdminWahana;

use App\Http\Controllers\Controller;
use App\Models\Wahana;
use App\Models\BookingManualWahana;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filter_type', 'monthly');

        $startDate = null;
        $endDate   = null;
        $title     = '';

        if ($filterType === 'weekly') {
            $weekDate  = $request->input('week_date', Carbon::now()->format('Y-m-d'));
            $startDate = Carbon::parse($weekDate)->startOfWeek();
            $endDate   = $startDate->copy()->endOfWeek();
            $title     = 'Pendapatan Mingguan (' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y') . ')';
        } elseif ($filterType === 'yearly') {
            $year      = $request->input('year', Carbon::now()->format('Y'));
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate   = $startDate->copy()->endOfYear();
            $title     = 'Pendapatan Tahunan (' . $year . ')';
        } else {
            $month     = $request->input('month', Carbon::now()->format('Y-m'));
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate   = $startDate->copy()->endOfMonth();
            $title     = 'Pendapatan Bulanan (' . $startDate->translatedFormat('F Y') . ')';
        }

        $bookings = BookingManualWahana::with('wahana')
            ->where('status_booking', '!=', 'cancelled')
            ->whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])
            ->latest()
            ->get();

        $totalPendapatan = $bookings->sum('total_harga');
        $wahanas         = Wahana::all();

        return view('admin_wahana.pendapatan.index', compact(
            'bookings',
            'wahanas',
            'totalPendapatan',
            'title',
            'filterType',
            'request'
        ));
    }
}
