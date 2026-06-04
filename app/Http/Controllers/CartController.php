<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cabin;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $totalHarga = 0;
        foreach ($cart as $item) {
            $totalHarga += $item['total_harga'];
        }

        return view('user.cart', compact('cart', 'totalHarga'));
    }

    public function add(Request $request, Cabin $cabin)
    {
        $request->validate([
            'dates' => 'required|string',
            'jumlah_kamar' => 'required|integer|min:1',
            'jumlah_tamu' => 'required|integer|min:1'
        ]);

        if ($request->jumlah_tamu > $cabin->kapasitas) {
            return back()->with('error', 'Jumlah tamu melebihi kapasitas kabin (maksimal ' . $cabin->kapasitas . ' orang).');
        }

        $dates = explode(' to ', $request->dates);
        if (count($dates) !== 2) {
            return back()->with('error', 'Silakan pilih rentang tanggal (Check-In dan Check-Out) di kalender.');
        }

        $tanggal_checkin = Carbon::parse($dates[0])->startOfDay();
        $tanggal_checkout = Carbon::parse($dates[1])->endOfDay();
        $jumlah_kamar = $request->jumlah_kamar;

        // Cek ketersediaan kamar
        $availableUnits = $cabin->units()->where('status', 'available')->get();

        // Jika kabin belum punya unit terdaftar sama sekali
        if ($availableUnits->isEmpty()) {
            return back()->with('error', 'Maaf, kabin ini belum memiliki unit kamar yang terdaftar. Silakan hubungi pengelola.');
        }

        $bookedOnlineUnitIds = Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkin', '<', $tanggal_checkout)
            ->where('tanggal_checkout', '>', $tanggal_checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        $bookedManualUnitIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'cancelled')
            ->where('tanggal_checkin', '<', $tanggal_checkout)
            ->where('tanggal_checkout', '>', $tanggal_checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        $allBookedUnitIds = array_unique(array_merge($bookedOnlineUnitIds, $bookedManualUnitIds));
        
        $availableCount = $availableUnits->whereNotIn('id', $allBookedUnitIds)->count();

        // Hitung kamar yang sudah ada di keranjang untuk kabin dan tanggal yang overlap
        $cart = session()->get('cart', []);
        $kamarDiKeranjang = 0;
        foreach ($cart as $item) {
            if ($item['cabin_id'] == $cabin->id) {
                $itemCheckin = Carbon::parse($item['tanggal_checkin']);
                $itemCheckout = Carbon::parse($item['tanggal_checkout']);
                
                // Cek overlap
                if ($tanggal_checkin->lt($itemCheckout) && $tanggal_checkout->gt($itemCheckin)) {
                    $kamarDiKeranjang += $item['jumlah_kamar'];
                }
            }
        }

        $sisaKamar = $availableCount - $kamarDiKeranjang;

        if ($jumlah_kamar < 1 || $sisaKamar < $jumlah_kamar) {
            return back()->with('error', 'Maaf, hanya tersisa ' . max(0, $sisaKamar) . ' kamar lagi pada tanggal tersebut (termasuk ' . $kamarDiKeranjang . ' kamar di keranjang Anda). Jumlah yang Anda minta (' . $jumlah_kamar . ') melebihi ketersediaan.');
        }

        // Kalkulasi Harga (Per Kamar)
        // is_couple hanya berlaku jika cabin memang punya harga_couple yang valid
        $isCouple = $request->has('is_couple') && !empty($request->is_couple) && $cabin->harga_couple > 0;
        $totalHargaBase = 0;
        
        $currentDate = $tanggal_checkin->copy()->startOfDay();
        $endDate = $tanggal_checkout->copy()->startOfDay();

        if ($tanggal_checkin->diffInDays($tanggal_checkout) < 1) {
            return back()->with('error', 'Minimal menginap adalah 1 malam (Check-In dan Check-Out tidak boleh di hari yang sama).');
        }

        while ($currentDate->lt($endDate)) {
            if ($isCouple && $cabin->harga_couple > 0) {
                $totalHargaBase += $cabin->harga_couple;
            } else {
                $day = $currentDate->dayOfWeek;
                // 0=Sunday, 1=Monday, ..., 6=Saturday
                // Weekend = Jumat(5) & Sabtu(6) & Minggu(0)
                if ($day == 0 || $day == 5 || $day == 6) {
                    $totalHargaBase += $cabin->harga_weekend;
                } else {
                    $totalHargaBase += $cabin->harga_weekday;
                }
            }
            $currentDate->addDay();
        }

        // Fasilitas Tambahan dihapus sesuai request
        $totalHargaFasilitas = 0;
        $fasilitasTambahanJson = null;

        // total_harga = harga malam × jumlah malam (Fasilitas ditiadakan)
        $totalHargaPerKamar = $totalHargaBase;

        // Cek apakah item yang sama persis sudah ada di keranjang
        $existingCartId = null;
        foreach ($cart as $key => $item) {
            if ($item['cabin_id'] == $cabin->id && 
                $item['tanggal_checkin'] == $tanggal_checkin->format('Y-m-d H:i:s') && 
                $item['tanggal_checkout'] == $tanggal_checkout->format('Y-m-d H:i:s') &&
                $item['is_couple'] == $isCouple) {
                $existingCartId = $key;
                break;
            }
        }

        if ($existingCartId) {
            // Jika ada, tambahkan jumlah kamar dan update total harga
            $cart[$existingCartId]['jumlah_kamar'] += $jumlah_kamar;
            $cart[$existingCartId]['total_harga'] = $totalHargaPerKamar * $cart[$existingCartId]['jumlah_kamar'];
        } else {
            // Jika belum ada, buat item baru
            $cartId = uniqid();
            $cart[$cartId] = [
                'id' => $cartId,
                'cabin_id' => $cabin->id,
                'cabin_name' => $cabin->name_cabin,
                'foto' => $cabin->galeris->first()->foto ?? null,
                'tanggal_checkin' => $tanggal_checkin->format('Y-m-d H:i:s'),
                'tanggal_checkout' => $tanggal_checkout->format('Y-m-d H:i:s'),
                'jumlah_kamar' => $jumlah_kamar,
                'jumlah_tamu' => $request->jumlah_tamu,
                'is_couple' => $isCouple,
                'fasilitas_tambahan' => $fasilitasTambahanJson,
                'total_harga_fasilitas' => $totalHargaFasilitas,
                'harga_per_kamar' => $totalHargaPerKamar, // simpan harga per kamar untuk kalkulasi ulang
                'total_harga' => $totalHargaPerKamar * $jumlah_kamar,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Kamar berhasil ditambahkan ke keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        $action = $request->input('action'); // 'increase' atau 'decrease'
        $current = $cart[$id]['jumlah_kamar'] ?? 1;

        if ($action === 'increase') {
            // Cek ketersediaan sebelum tambah
            $cabin = Cabin::find($cart[$id]['cabin_id']);
            if (!$cabin) return back()->with('error', 'Kabin tidak ditemukan.');

            $tanggal_checkin  = Carbon::parse($cart[$id]['tanggal_checkin']);
            $tanggal_checkout = Carbon::parse($cart[$id]['tanggal_checkout']);

            $allUnits = $cabin->units()->where('status', 'available')->get();

            $bookedOnlineIds = \App\Models\Booking::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'ditolak')
                ->where('tanggal_checkin', '<', $tanggal_checkout)
                ->where('tanggal_checkout', '>', $tanggal_checkin)
                ->whereNotNull('cabin_unit_id')
                ->pluck('cabin_unit_id')->toArray();

            $bookedManualIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'cancelled')
                ->where('tanggal_checkin', '<', $tanggal_checkout)
                ->where('tanggal_checkout', '>', $tanggal_checkin)
                ->whereNotNull('cabin_unit_id')
                ->pluck('cabin_unit_id')->toArray();

            $availableCount = $allUnits->whereNotIn('id', array_unique(array_merge($bookedOnlineIds, $bookedManualIds)))->count();

            // Hitung kamar yang sudah ada di keranjang untuk kabin dan tanggal yang overlap (kecuali item ini sendiri)
            $kamarDiKeranjang = 0;
            foreach ($cart as $cartItemId => $item) {
                if ($cartItemId != $id && $item['cabin_id'] == $cabin->id) {
                    $itemCheckin = Carbon::parse($item['tanggal_checkin']);
                    $itemCheckout = Carbon::parse($item['tanggal_checkout']);
                    
                    if ($tanggal_checkin->lt($itemCheckout) && $tanggal_checkout->gt($itemCheckin)) {
                        $kamarDiKeranjang += $item['jumlah_kamar'];
                    }
                }
            }

            $sisaKamar = $availableCount - $kamarDiKeranjang;

            if ($current >= $sisaKamar) {
                return back()->with('error', 'Maaf, kamar yang tersisa pada tanggal ini hanya ' . max(0, $sisaKamar) . ' unit (di luar keranjang Anda).');
            }
            $cart[$id]['jumlah_kamar'] = $current + 1;
            
            // Update total harga
            $hargaPerKamar = $cart[$id]['harga_per_kamar'] ?? ($cart[$id]['total_harga'] / $current); // fallback jika harga_per_kamar belum tersimpan
            $cart[$id]['total_harga'] = $hargaPerKamar * $cart[$id]['jumlah_kamar'];

        } elseif ($action === 'decrease') {
            if ($current <= 1) {
                // Jika sudah 1, hapus item
                unset($cart[$id]);
                session()->put('cart', $cart);
                return back()->with('success', 'Item dihapus dari keranjang.');
            }
            $cart[$id]['jumlah_kamar'] = $current - 1;
            
            // Update total harga
            $hargaPerKamar = $cart[$id]['harga_per_kamar'] ?? ($cart[$id]['total_harga'] / $current); // fallback jika harga_per_kamar belum tersimpan
            $cart[$id]['total_harga'] = $hargaPerKamar * $cart[$id]['jumlah_kamar'];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Jumlah kamar berhasil diperbarui.');
    }

    public function checkout()
    {
        $cart = session()->get('cart');
        
        if (!$cart || count($cart) == 0) {
            return redirect()->route('welcome')->with('error', 'Keranjang Anda kosong.');
        }

        $orderId = 'ORD-' . date('md') . rand(10, 99);
        $grandTotal = 0;

        foreach ($cart as $item) {
            $cabin = Cabin::find($item['cabin_id']);
            $jumlah_kamar = $item['jumlah_kamar'];
            
            // Validasi ulang ketersediaan saat checkout
            $availableUnits = $cabin->units()->where('status', 'available')->get();

            $bookedOnlineUnitIds = Booking::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'ditolak')
                ->where('tanggal_checkin', '<', $item['tanggal_checkout'])
                ->where('tanggal_checkout', '>', $item['tanggal_checkin'])
                ->whereNotNull('cabin_unit_id')
                ->pluck('cabin_unit_id')
                ->toArray();

            $bookedManualUnitIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'cancelled')
                ->where('tanggal_checkin', '<', $item['tanggal_checkout'])
                ->where('tanggal_checkout', '>', $item['tanggal_checkin'])
                ->whereNotNull('cabin_unit_id')
                ->pluck('cabin_unit_id')
                ->toArray();

            $allBookedUnitIds = array_unique(array_merge($bookedOnlineUnitIds, $bookedManualUnitIds));
            
            // Ambil N unit yang kosong
            $availableUnitIds = $availableUnits->whereNotIn('id', $allBookedUnitIds)->pluck('id')->take($jumlah_kamar);

            if ($availableUnitIds->count() < $jumlah_kamar) {
                return back()->with('error', 'Maaf, ' . $cabin->name_cabin . ' tidak lagi memiliki ' . $jumlah_kamar . ' kamar pada tanggal yang dipilih. Silakan hapus atau ubah item di keranjang.');
            }

            $hargaPerKamar = $item['harga_per_kamar'] ?? ($item['total_harga'] / $jumlah_kamar);

            // Create bookings per room
            foreach ($availableUnitIds as $unitId) {
                Booking::create([
                    'order_id' => $orderId,
                    'user_id' => Auth::id(),
                    'cabin_id' => $cabin->id,
                    'cabin_unit_id' => $unitId,
                    'tanggal_checkin' => $item['tanggal_checkin'],
                    'tanggal_checkout' => $item['tanggal_checkout'],
                    'jumlah_tamu' => $item['jumlah_tamu'],
                    'is_couple' => $item['is_couple'],
                    'fasilitas_tambahan' => $item['fasilitas_tambahan'],
                    'total_harga_fasilitas' => $item['total_harga_fasilitas'] ?? 0,
                    'total_harga' => $hargaPerKamar,
                    'status_booking' => 'pending'
                ]);
            }
            $grandTotal += $item['total_harga'];
        }

        // Create 1 Pembayaran for the Order
        Pembayaran::create([
            'order_id' => $orderId,
            'booking_id' => null, // We group by order_id now
            'metode_pembayaran' => null,
            'tanggal_pembayaran' => null,
            'jumlah_bayar' => $grandTotal,
            'bukti_pembayaran' => null,
            'status_pembayaran' => 'pending'
        ]);

        session()->forget('cart');

        return redirect()->route('user.dashboard')->with('success', 'Checkout berhasil! Silakan lakukan pembayaran untuk order ' . $orderId);
    }
}
