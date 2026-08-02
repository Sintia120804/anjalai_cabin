<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Booking;
use App\Models\BookingManual;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'pengunjung')
            ->withCount(['bookings' => function($query) {
                $query->where('status_booking', '!=', 'ditolak');
            }])
            ->latest()
            ->get();
            
        return view('admin.customer.index', compact('customers'));
    }

    public function destroy(User $customer)
    {
        if ($customer->role === 'pengunjung') {
            $customer->delete();
            return back()->with('success', 'Customer berhasil dihapus.');
        }
        return back()->with('error', 'Gagal menghapus customer.');
    }
}
