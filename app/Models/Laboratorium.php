<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    use HasFactory;

    protected $table = 'laboratorium';

    protected $fillable = [
        'kunjungan_id',
        'nama_pemeriksaan',
        'nomor_pemeriksaan',
        'tanggal_permintaan',
        'jam_permintaan',
        'dokter_pengirim',
        'nomor_telepon_dokter',
        'nama_fasilitas_pelayanan',
        'unit_pengirim',
        'prioritas_pemeriksaan',
        'diagnosis_masalah',
        'catatan_permintaan',
        'metode_pengiriman',
        'asal_sumber_spesimen',
        'lokasi_pengambilan_spesimen',
        'jumlah_spesimen',
        'volume_spesimen',
        'metode_pengambilan_spesimen',
        'tanggal_pengambilan_spesimen',
        'jam_pengambilan_spesimen',
        'kondisi_spesimen',
        'tanggal_fiksasi_spesimen',
        'jam_fiksasi_spesimen',
        'cairan_fiksasi',
        'volume_cairan_fiksasi',
        'petugas_mengambil_spesimen',
        'petugas_mengantarkan_spesimen',
        'petugas_menerima_spesimen',
        'petugas_menganalisis_spesimen',
        'tanggal_pemeriksaan_spesimen',
        'jam_pemeriksaan_spesimen',
        'nilai_hasil_pemeriksaan',
        'nilai_moral',
        'nilai_rujukan',
        'nilai_kritis',
        'interpretasi_hasil',
        'dokter_validasi',
        'dokter_interpretasi',
        'tanggalpemeriksaan_keluar',
        'jam_pemeriksaan_keluar',
        'tanggal_pemeriksaan_diterima',
        'jam_pemeriksaan_diterima',
        'fasilitas_kesehatan_pemeriksaan',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
