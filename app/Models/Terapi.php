<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terapi extends Model
{
    use HasFactory;

    protected $table = 'terapi';

    protected $fillable = [
        'kunjungan_id',
        'obat_id',
        'nama_tindakan',
        'petugas',
        'tanggal_pelaksanaan_tindakan',
        'jam_mulai_tindakan',
        'jam_selesai_tindakan',
        'alat_medis',
        'bmhp',
    ];

    public function obat()
    {
        return $this->belongsTo(ObatResep::class, 'obat_id');
    }
}
