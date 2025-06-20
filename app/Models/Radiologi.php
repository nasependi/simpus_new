<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radiologi extends Model
{
    use HasFactory;

    protected $table = 'radiologi';

    protected $fillable = [
        'kunjungan_id',
        'nama_pemeriksaan',
        'jenis_pemeriksaan',
        'nomor_pemeriksaan',
        'tanggal_permintaan',
        'jam_permintaan',
        'dokter_pengirim',
        'nomor_telepon_dokter',
        'nama_fasilitas_radiologi',
        'unit_pengirim_radiologi',
        'prioritas_pemeriksaan_radiologi',
        'diagnosis_kerja',
        'catatan_permintaan',
        'metode_penyampaian_pemeriksaan',
        'status_alergi',
        'status_kehamilan',
        'tanggal_pemeriksaan',
        'jam_pemeriksaan',
        'jenis_bahan_kontras',
        'foto_hasil',
        'nama_dokter_pemeriksaan',
        'interpretasi_radiologi',
    ];
}
