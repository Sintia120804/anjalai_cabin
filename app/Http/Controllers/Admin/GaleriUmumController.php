<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriUmumController extends Controller
{
    public function index()
    {
        $galeris = GaleriUmum::latest()->get();
        return view('admin.galeri_umum.index', compact('galeris'));
    }

    public function create()
    {
        return view('admin.galeri_umum.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:10048',
            'caption' => 'nullable|string|max:255'
        ]);

        $path = $request->file('foto')->store('galeri_umum', 'public');

        GaleriUmum::create([
            'foto' => $path,
            'caption' => $request->caption
        ]);

        return redirect()->route('admin.galeri_umum.index')->with('success', 'Foto Galeri berhasil ditambahkan.');
    }

    public function edit(GaleriUmum $galeri_umum)
    {
        return view('admin.galeri_umum.edit', compact('galeri_umum'));
    }

    public function update(Request $request, GaleriUmum $galeri_umum)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10048',
            'caption' => 'nullable|string|max:255'
        ]);

        $data = ['caption' => $request->caption];

        if ($request->hasFile('foto')) {
            if ($galeri_umum->foto) {
                Storage::disk('public')->delete($galeri_umum->foto);
            }
            $path = $request->file('foto')->store('galeri_umum', 'public');
            $data['foto'] = $path;
        }

        $galeri_umum->update($data);

        return redirect()->route('admin.galeri_umum.index')->with('success', 'Foto Galeri berhasil diperbarui.');
    }

    public function destroy(GaleriUmum $galeri_umum)
    {
        if ($galeri_umum->foto) {
            Storage::disk('public')->delete($galeri_umum->foto);
        }
        $galeri_umum->delete();

        return redirect()->route('admin.galeri_umum.index')->with('success', 'Foto Galeri berhasil dihapus.');
    }
}
