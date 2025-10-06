<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanLab extends Model
{
    protected $table = 'pemeriksaan_lab';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];
}
