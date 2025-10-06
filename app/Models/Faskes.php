<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faskes extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_faskes',
        'no_surat_izin',
        'no_telp',
        'alamat',
        'email',
        'website',
    ];
}
