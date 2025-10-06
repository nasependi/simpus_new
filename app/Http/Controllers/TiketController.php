<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;

class TiketController extends Controller
{
    public function print($id)
    {
        $kunjungan = Kunjungan::with(['pasien', 'obatResep.obat'])->findOrFail($id);

        return view('print.tiket', compact('kunjungan'));
    }
}
