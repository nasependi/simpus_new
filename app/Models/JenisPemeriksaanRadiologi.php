<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPemeriksaanRadiologi extends Model
{
    protected $table = 'jenis_pemeriksaan_radiologi';

    protected $fillable = [
        'kode',
        'nama',
    ];

    public function pemeriksaanRadiologi()
    {
        return $this->hasMany(Radiologi::class, 'jenis_pemeriksaan_id');
    }
}
