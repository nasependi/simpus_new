<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatResep extends Model
{
    use HasFactory;

    protected $table = 'obat_resep';

    protected $fillable = [
        'kunjungan_id',
        'tb_pasien',
        'bb_pasien',
        'id_resep',
        'nama_obat',
        'id_obat',
        'sediaan',
        'jumlah_obat',
        'metode_pemberian',
        'dosis_diberikan',
        'unit',
        'frekuensi',
        'aturan_tambahan',
        'catatan_resep',
        'dokter_penulis_resep',
        'nomor_telepon_dokter',
        'tanggal_penulisan_resep',
        'jam_penulisan_resep',
        'ttd_dokter',
        'status_resep',
        'pengkajian_resep',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat',);
    }

    // Relasi ke Kunjungan
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }
}
