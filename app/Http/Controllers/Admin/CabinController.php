<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabin;
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
            'name_cabin' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_per_malam' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,tidak tersedia',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5048'
        ]);

        $cabin = Cabin::create([
            'name_cabin' => $request->name_cabin,
            'deskripsi' => $request->deskripsi,
            'harga_per_malam' => $request->harga_per_malam,
            'kapasitas' => $request->kapasitas,
            'status' => $request->status,
        ]);

        // Upload ke Galeris
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('cabins', $filename, 'public');
                
                Galeri::create([
                    'cabin_id' => $cabin->id,
                    'foto' => $path
                ]);
            }
        }

        return redirect()->route('admin.cabin.index')->with('success', 'Cabin & Galeri berhasil ditambahkan.');
    }

    public function edit(Cabin $cabin)
    {
        $cabin->load('galeris');
        return view('admin.cabin.edit', compact('cabin'));
    }

    public function update(Request $request, Cabin $cabin)
    {
        $request->validate([
            'name_cabin' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_per_malam' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,tidak tersedia',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5048',
            'delete_fotos' => 'array'
        ]);

        $cabin->update([
            'name_cabin' => $request->name_cabin,
            'deskripsi' => $request->deskripsi,
            'harga_per_malam' => $request->harga_per_malam,
            'kapasitas' => $request->kapasitas,
            'status' => $request->status,
        ]);

        // Hapus foto jika ada yang dicentang
        if ($request->has('delete_fotos')) {
            $galerisToDelete = Galeri::whereIn('id', $request->delete_fotos)->where('cabin_id', $cabin->id)->get();
            foreach ($galerisToDelete as $galeri) {
                Storage::disk('public')->delete($galeri->foto);
                $galeri->delete();
            }
        }

        // Upload foto baru
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('cabins', $filename, 'public');
                
                Galeri::create([
                    'cabin_id' => $cabin->id,
                    'foto' => $path
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
