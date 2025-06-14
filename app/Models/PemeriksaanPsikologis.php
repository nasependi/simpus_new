<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanPsikologis extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_psikologis';

    protected $guarded = [
        'id'
    ];
}
