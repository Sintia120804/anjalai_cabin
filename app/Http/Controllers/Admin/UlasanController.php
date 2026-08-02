<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ulasan;

class UlasanController extends Controller
{
    public function index()
    {
        $ulasans = Ulasan::latest()->get();
        return view('admin.ulasan.index', compact('ulasans'));
    }

    public function destroy(Ulasan $ulasan)
    {
        $ulasan->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
