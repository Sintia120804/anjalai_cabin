<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabin;
use App\Models\CabinUnit;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CabinController extends Controller
{
    public function index()
    {
        $cabins = Cabin::with('galeris')->latest()->get();
        return view('admin.cabin.index', compact('cabins'));
    }

    public function create()
    {
        return view('admin.cabin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_cabin'          => 'required|string|max:255',
            'deskripsi'           => 'nullable|string',
            'harga_weekday'       => 'required|numeric|min:0',
            'harga_weekend'       => 'required|numeric|min:0',
            'harga_couple'        => 'nullable|numeric|min:0',
            'kapasitas'           => 'required|integer|min:1',
            'status'              => 'required|in:tersedia,tidak tersedia',
            'fasilitas'           => 'nullable|array',
            'fotos.*'             => 'image|mimes:jpeg,png,jpg,webp|max:5048',
            'units'               => 'nullable|array',
            'units.*.unit_name'   => 'required_with:units|string|max:255',
            'units.*.status'      => 'required_with:units|in:available,maintenance',
        ]);

        $cabin = Cabin::create([
            'name_cabin'    => $request->name_cabin,
            'deskripsi'     => $request->deskripsi,
            'harga_weekday' => $request->harga_weekday,
            'harga_weekend' => $request->harga_weekend,
            'harga_couple'  => $request->harga_couple,
            'kapasitas'     => $request->kapasitas,
            'status'        => $request->status,
            'fasilitas'     => $request->fasilitas,
        ]);

        // Simpan Unit/Kamar yang diinput langsung dari form create
        if ($request->filled('units')) {
            foreach ($request->units as $unit) {
                if (!empty($unit['unit_name'])) {
                    CabinUnit::create([
                        'cabin_id'  => $cabin->id,
                        'unit_name' => $unit['unit_name'],
                        'status'    => $unit['status'] ?? 'available',
                    ]);
                }
            }
        }

        // Upload ke Galeris
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('cabins', $filename, 'public');

                Galeri::create([
                    'cabin_id' => $cabin->id,
                    'foto'     => $path
                ]);
            }
        }

        return redirect()->route('admin.cabin.index')->with('success', 'Cabin, Kamar, & Galeri berhasil ditambahkan.');
    }

    public function edit(Cabin $cabin)
    {
        $cabin->load('galeris', 'units');
        return view('admin.cabin.edit', compact('cabin'));
    }

    public function update(Request $request, Cabin $cabin)
    {
        $request->validate([
            'name_cabin'                        => 'required|string|max:255',
            'deskripsi'                         => 'nullable|string',
            'harga_weekday'                     => 'required|numeric|min:0',
            'harga_weekend'                     => 'required|numeric|min:0',
            'harga_couple'                      => 'nullable|numeric|min:0',
            'kapasitas'                         => 'required|integer|min:1',
            'status'                            => 'required|in:tersedia,tidak tersedia',
            'fasilitas'                         => 'nullable|array',
            'fotos.*'                           => 'image|mimes:jpeg,png,jpg,webp|max:5048',
            'delete_fotos'                      => 'nullable|array',
            'delete_units'                      => 'nullable|array',
            'existing_units.*.unit_name'        => 'required|string|max:255',
            'existing_units.*.status'           => 'required|in:available,maintenance',
            'units'                             => 'nullable|array',
            'units.*.unit_name'                 => 'required_with:units|string|max:255',
            'units.*.status'                    => 'required_with:units|in:available,maintenance',
        ]);

        $cabin->update([
            'name_cabin'    => $request->name_cabin,
            'deskripsi'     => $request->deskripsi,
            'harga_weekday' => $request->harga_weekday,
            'harga_weekend' => $request->harga_weekend,
            'harga_couple'  => $request->harga_couple,
            'kapasitas'     => $request->kapasitas,
            'status'        => $request->status,
            'fasilitas'     => $request->fasilitas,
        ]);

        // 1. Hapus unit yang dicentang
        if ($request->has('delete_units')) {
            CabinUnit::whereIn('id', $request->delete_units)
                ->where('cabin_id', $cabin->id)
                ->delete();
        }

        // 2. Update unit yang sudah ada (nama & status)
        if ($request->has('existing_units')) {
            foreach ($request->existing_units as $unitId => $data) {
                CabinUnit::where('id', $unitId)
                    ->where('cabin_id', $cabin->id)
                    ->update([
                        'unit_name' => $data['unit_name'],
                        'status'    => $data['status'],
                    ]);
            }
        }

        // 3. Tambah unit baru
        if ($request->filled('units')) {
            foreach ($request->units as $unit) {
                if (!empty($unit['unit_name'])) {
                    CabinUnit::create([
                        'cabin_id'  => $cabin->id,
                        'unit_name' => $unit['unit_name'],
                        'status'    => $unit['status'] ?? 'available',
                    ]);
                }
            }
        }

        // 4. Hapus foto jika ada yang dicentang
        if ($request->has('delete_fotos')) {
            $galerisToDelete = Galeri::whereIn('id', $request->delete_fotos)->where('cabin_id', $cabin->id)->get();
            foreach ($galerisToDelete as $galeri) {
                Storage::disk('public')->delete($galeri->foto);
                $galeri->delete();
            }
        }

        // 5. Upload foto baru
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('cabins', $filename, 'public');

                Galeri::create([
                    'cabin_id' => $cabin->id,
                    'foto'     => $path
                ]);
            }
        }

        return redirect()->route('admin.cabin.index')->with('success', 'Data Cabin berhasil diperbarui.');
    }

    public function destroy(Cabin $cabin)
    {
        foreach ($cabin->galeris as $galeri) {
            Storage::disk('public')->delete($galeri->foto);
        }
        
        $cabin->delete();
        return redirect()->route('admin.cabin.index')->with('success', 'Cabin dan seluruh galeri berhasil dihapus.');
    }
}
