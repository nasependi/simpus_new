<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersetujuanTindakan extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_tindakan';

    // App\Models\PersetujuanTindakan.php
    protected $fillable = [
        'kunjungan_id',
        'nama_dokter',
        'nama_petugas_mendampingi',
        'nama_keluarga_pasien',
        'tindakan_dilakukan',
        'konsekuensi_tindakan',
        'tanggal_tindakan',
        'jam_tindakan',
        'ttd_dokter',
        'ttd_pasien_keluarga',
        'saksi1',
        'saksi2',
        'persetujuan_penolakan',
    ];


    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
