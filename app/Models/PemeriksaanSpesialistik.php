<?php

// app/Models/PemeriksaanSpesialistik.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanSpesialistik extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_spesialistik';

    protected $fillable = [
        'kunjungan_id',
        'nama_obat',
        'dosis',
        'waktu_penggunaan',
        'rencana_rawat',
        'intruksi_medik',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}

