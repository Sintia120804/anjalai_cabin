<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\BookingManual;
use App\Models\Pembayaran;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filter_type', 'monthly');
        
        $startDate = null;
        $endDate = null;
        $title = '';

        if ($filterType === 'weekly') {
            $weekDate = $request->input('week_date', Carbon::now()->format('Y-m-d'));
            $startDate = Carbon::parse($weekDate)->startOfWeek();
            $endDate = $startDate->copy()->endOfWeek();
            $title = 'Laporan Mingguan (' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y') . ')';
        } elseif ($filterType === 'yearly') {
            $year = $request->input('year', Carbon::now()->format('Y'));
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate = $startDate->copy()->endOfYear();
            $title = 'Laporan Tahunan (' . $year . ')';
        } else {
            // default monthly
            $month = $request->input('month', Carbon::now()->format('Y-m'));
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $title = 'Laporan Bulanan (' . $startDate->translatedFormat('F Y') . ')';
        }

        $onlineBookings = Booking::with(['user', 'cabin'])
            ->whereBetween('tanggal_checkin', [$startDate, $endDate])
            ->latest()
            ->get();
            
        $manualBookings = BookingManual::with(['cabin'])
            ->whereBetween('tanggal_checkin', [$startDate, $endDate])
            ->latest()
            ->get();

        $onlineOrderIds = $onlineBookings->pluck('order_id')->unique();
        $revenue = Pembayaran::where('status_pembayaran', 'diterima')
            ->whereIn('order_id', $onlineOrderIds)
            ->sum('jumlah_bayar') + 
            $manualBookings->where('status_booking', '!=', 'cancelled')->sum('total_harga');

        if ($request->has('export_pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact('onlineBookings', 'manualBookings', 'revenue', 'title'));
            return $pdf->download('Laporan_Pendapatan_' . str_replace(' ', '_', $title) . '.pdf');
        }

        return view('admin.laporan.index', compact('onlineBookings', 'manualBookings', 'revenue', 'title', 'filterType', 'request'));
    }
}
