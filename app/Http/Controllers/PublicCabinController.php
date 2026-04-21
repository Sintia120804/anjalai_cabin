<?php

namespace App\Http\Controllers;

use App\Models\Cabin;
use App\Models\Wahana;
use App\Models\Tentang;
use App\Models\GaleriUmum;
use Illuminate\Http\Request;

class PublicCabinController extends Controller
{
    public function index(Request $request)
    {
        $query = Cabin::with('galeris')->where('status', 'tersedia');

        if ($request->filled('checkin') && $request->filled('checkout')) {
            $checkin = $request->checkin;
            $checkout = $request->checkout;

            $query->whereDoesntHave('bookings', function ($q) use ($checkin, $checkout) {
                $q->where('status_booking', '!=', 'ditolak')
                  ->where(function ($q2) use ($checkin, $checkout) {
                      $q2->where('tanggal_checkin', '<', $checkout)
                         ->where('tanggal_checkout', '>', $checkin);
                  });
            })->whereDoesntHave('booking_manuals', function ($q) use ($checkin, $checkout) {
                $q->where(function ($q2) use ($checkin, $checkout) {
                      $q2->where('tanggal_checkin', '<', $checkout)
                         ->where('tanggal_checkout', '>', $checkin);
                });
            });
        }

        // Get available cabins
        $cabins = $query->latest()->get();

        $wahanas = Wahana::latest()->get();
        $tentangs = Tentang::latest()->get();
        $galeriUmum = GaleriUmum::latest()->get();

        return view('welcome', compact('cabins', 'wahanas', 'tentangs', 'galeriUmum'));
    }

    public function show(Cabin $cabin)
    {
        // Ensure cabin is available
        if ($cabin->status !== 'tersedia') {
            abort(404);
        }

        $cabin->load('galeris'); // Load galeris

        // Ambil data tanggal yang sudah dipesan untuk kalender
        $bookedDates = \App\Models\Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->select('tanggal_checkin', 'tanggal_checkout')
            ->get();

        $manualBookedDates = \App\Models\BookingManual::where('cabin_id', $cabin->id)
            ->select('tanggal_checkin', 'tanggal_checkout')
            ->get();

        $fasilitasTambahan = \App\Models\FasilitasTambahan::all();

        return view('cabin.show', compact('cabin', 'bookedDates', 'manualBookedDates', 'fasilitasTambahan'));
    }
}
