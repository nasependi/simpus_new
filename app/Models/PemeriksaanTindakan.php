<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanTindakan extends Model
{
    protected $table = 'pemeriksaan_tindakan';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];
}
