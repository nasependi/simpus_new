<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatKesadaran extends Model
{
    use HasFactory;

    protected $table = 'tingkat_kesadaran';

    protected $fillable = [
        'keterangan',
        'nilai',
    ];
}