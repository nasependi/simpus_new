<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pendidikan extends Model
{
    protected $table = 'pendidikan';

    protected $fillable = ['kode', 'nama_pendidikan'];
}