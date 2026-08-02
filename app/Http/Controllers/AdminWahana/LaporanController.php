<?php

namespace App\Http\Controllers\AdminWahana;

use App\Http\Controllers\Controller;
use App\Models\Wahana;
use App\Models\BookingManualWahana;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
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
            $title     = 'Laporan Wahana Mingguan (' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y') . ')';
        } elseif ($filterType === 'yearly') {
            $year      = $request->input('year', Carbon::now()->format('Y'));
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate   = $startDate->copy()->endOfYear();
            $title     = 'Laporan Wahana Tahunan (' . $year . ')';
        } else {
            $month     = $request->input('month', Carbon::now()->format('Y-m'));
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate   = $startDate->copy()->endOfMonth();
            $title     = 'Laporan Wahana Bulanan (' . $startDate->translatedFormat('F Y') . ')';
        }

        $wahanas     = Wahana::all();
        $totalWahana = $wahanas->count();

        $bookings = BookingManualWahana::with('wahana', 'admin')
            ->whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])
            ->latest()
            ->get();

        $totalPendapatan = $bookings->where('status_booking', '!=', 'cancelled')->sum('total_harga');

        if ($request->has('export_pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin_wahana.laporan.pdf', compact(
                'wahanas',
                'bookings',
                'totalPendapatan',
                'totalWahana',
                'title'
            ));
            return $pdf->download('Laporan_Wahana_' . str_replace(' ', '_', $title) . '.pdf');
        }

        return view('admin_wahana.laporan.index', compact(
            'wahanas',
            'bookings',
            'totalWahana',
            'totalPendapatan',
            'title',
            'filterType',
            'request'
        ));
    }
}
