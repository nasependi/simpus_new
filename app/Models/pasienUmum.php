<?php

// app/Models/PasienUmum.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasienUmum extends Model
{
    use HasFactory;

    protected $table = 'pasien_umum';

    protected $guarded = [];

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

    public function province()
    {
        return $this->belongsTo(Province::class, 'prov_id');
    }
}
