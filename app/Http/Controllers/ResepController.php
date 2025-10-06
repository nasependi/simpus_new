<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function print($id)
    {
        $kunjungan = Kunjungan::with(['pasien', 'obatResep.obat'])->findOrFail($id);

        // langsung return view, biar tampil di tab baru + auto print
        return view('print.resep', compact('kunjungan'));
    }
}
