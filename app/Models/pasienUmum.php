<?php

// app/Models/PasienUmum.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasienUmum extends Model
{
    use HasFactory;

    protected $table = 'pasien_umum';

    protected $fillable = [
        'nama_lengkap',
        'no_rekamedis',
        'nik',
        'paspor',
        'ibu_kandung',
        'tempat_lahir',
        'tanggal_lahir',
        'jk_id',
        'agama_id',
        'suku',
        'bahasa_dikuasai',
        'alamat_lengkap',
        'rt_id',
        'rw_id',
        'kel_id',
        'kec_id',
        'kab_id',
        'kodepos_id',
        'prov_id',
        'alamat_domisili',
        'domisili_rt',
        'domisili_rw',
        'domisili_kel',
        'domisili_kec',
        'domisili_kab',
        'domisili_kodepos',
        'domisili_prov',
        'domisili_negara',
        'no_rumah',
        'no_hp',
        'pendidikan_id',
        'pekerjaan_id',
        'statusnikah_id'
    ];

    public function jenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk_id');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id');
    }

    public function statusPernikahan()
    {
        return $this->belongsTo(StatusPernikahan::class, 'statusnikah_id');
    }
}
