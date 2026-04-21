<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FasilitasTambahan;
use Illuminate\Http\Request;

class FasilitasTambahanController extends Controller
{
    public function index()
    {
        $fasilitas = FasilitasTambahan::latest()->paginate(10);
        return view('admin.fasilitas_tambahan.index', compact('fasilitas'));
    }

    public function create()
    {
        return view('admin.fasilitas_tambahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        FasilitasTambahan::create($request->all());
        return redirect()->route('admin.fasilitas_tambahan.index')->with('success', 'Fasilitas Tambahan berhasil ditambahkan!');
    }

    public function edit(FasilitasTambahan $fasilitas_tambahan)
    {
        return view('admin.fasilitas_tambahan.edit', compact('fasilitas_tambahan'));
    }

    public function update(Request $request, FasilitasTambahan $fasilitas_tambahan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        $fasilitas_tambahan->update($request->all());
        return redirect()->route('admin.fasilitas_tambahan.index')->with('success', 'Fasilitas Tambahan berhasil diperbarui!');
    }

    public function destroy(FasilitasTambahan $fasilitas_tambahan)
    {
        $fasilitas_tambahan->delete();
        return redirect()->route('admin.fasilitas_tambahan.index')->with('success', 'Fasilitas Tambahan berhasil dihapus!');
    }
}
