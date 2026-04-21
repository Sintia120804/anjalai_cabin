<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wahana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WahanaController extends Controller
{
    public function index()
    {
        $wahanas = Wahana::latest()->get();
        return view('admin.wahana.index', compact('wahanas'));
    }

    public function create()
    {
        return view('admin.wahana.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048'
        ]);

        $data = $request->only(['nama', 'deskripsi']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('wahana', 'public');
            $data['foto'] = $path;
        }

        Wahana::create($data);

        return redirect()->route('admin.wahana.index')->with('success', 'Wahana berhasil ditambahkan.');
    }

    public function edit(Wahana $wahana)
    {
        return view('admin.wahana.edit', compact('wahana'));
    }

    public function update(Request $request, Wahana $wahana)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048'
        ]);

        $data = $request->only(['nama', 'deskripsi']);

        if ($request->hasFile('foto')) {
            if ($wahana->foto) {
                Storage::disk('public')->delete($wahana->foto);
            }
            $path = $request->file('foto')->store('wahana', 'public');
            $data['foto'] = $path;
        }

        $wahana->update($data);

        return redirect()->route('admin.wahana.index')->with('success', 'Wahana berhasil diperbarui.');
    }

    public function destroy(Wahana $wahana)
    {
        if ($wahana->foto) {
            Storage::disk('public')->delete($wahana->foto);
        }
        $wahana->delete();

        return redirect()->route('admin.wahana.index')->with('success', 'Wahana berhasil dihapus.');
    }
}
