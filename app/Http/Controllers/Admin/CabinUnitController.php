<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cabin;
use App\Models\CabinUnit;

class CabinUnitController extends Controller
{
    public function index(Cabin $cabin)
    {
        $units = $cabin->units()->get();
        return view('admin.cabin_units.index', compact('cabin', 'units'));
    }

    public function store(Request $request, Cabin $cabin)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255',
            'status' => 'required|in:available,maintenance',
        ]);

        $cabin->units()->create([
            'unit_name' => $request->unit_name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Unit berhasil ditambahkan!');
    }

    public function destroy(CabinUnit $unit)
    {
        $unit->delete();
        return redirect()->back()->with('success', 'Unit berhasil dihapus!');
    }
}
