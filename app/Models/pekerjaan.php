<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pekerjaan extends Model
{
    protected $table = 'pekerjaan';
    
    protected $fillable = ['kode', 'nama_pekerjaan'];
}