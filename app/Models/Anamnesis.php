<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anamnesis extends Model
{
    use HasFactory;

    protected $table = 'anamnesis';

    protected $fillable = [
        'kunjungan_id',
        'keluhan_utama',
        'riwayat_penyakit',
        'riwayat_alergi',
        'riwayat_pengobatan',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
