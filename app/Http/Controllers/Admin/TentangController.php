<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TentangController extends Controller
{
    public function index()
    {
        $tentangs = Tentang::latest()->get();
        return view('admin.tentang.index', compact('tentangs'));
    }

    public function create()
    {
        return view('admin.tentang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048'
        ]);

        $data = $request->only(['judul', 'deskripsi']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('tentang', 'public');
            $data['foto'] = $path;
        }

        Tentang::create($data);

        return redirect()->route('admin.tentang.index')->with('success', 'Informasi Tentang berhasil ditambahkan.');
    }

    public function edit(Tentang $tentang)
    {
        return view('admin.tentang.edit', compact('tentang'));
    }

    public function update(Request $request, Tentang $tentang)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048'
        ]);

        $data = $request->only(['judul', 'deskripsi']);

        if ($request->hasFile('foto')) {
            if ($tentang->foto) {
                Storage::disk('public')->delete($tentang->foto);
            }
            $path = $request->file('foto')->store('tentang', 'public');
            $data['foto'] = $path;
        }

        $tentang->update($data);

        return redirect()->route('admin.tentang.index')->with('success', 'Informasi Tentang berhasil diperbarui.');
    }

    public function destroy(Tentang $tentang)
    {
        if ($tentang->foto) {
            Storage::disk('public')->delete($tentang->foto);
        }
        $tentang->delete();

        return redirect()->route('admin.tentang.index')->with('success', 'Informasi Tentang berhasil dihapus.');
    }
}
