<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralConsent extends Model
{
    protected $table = 'general_consent';

    protected $fillable = [
        'kunjungan_id',
        'tanggal',
        'jam',
        'persetujuan_pasien',
        'informasi_ketentuan_pembayaran',
        'informasi_hak_kewajiban',
        'informasi_tata_tertib_rs',
        'kebutuhan_penerjemah_bahasa',
        'kebutuhan_rohaniawan',
        'kerahasiaan_informasi',
        'pemeriksaan_ke_pihak_penjamin',
        'pemeriksaan_diakses_peserta_didik',
        'anggota_keluarga_dapat_akses',
        'akses_fasyankes_rujukan',
        'penanggung_jawab',
        'petugas_pemberi_penjelasan',
        'ttd_penanggung_jawab',
        'ttd_petugas',
    ];

    // Relasi ke Kunjungan
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
