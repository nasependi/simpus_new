<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayiBaruLahir extends Model
{
    use HasFactory;

    protected $table = 'bayi_barulahir';

    protected $fillable = [
        'nama_bayi',
        'nik_ibuk',
        'no_rekamedis',
        'tempat_lahir',
        'tanggal_lahir',
        'jam_lahir',
        'jk_id',
    ];

    public function jenisKelamin()
    {
        return $this->belongsTo(JenisKelamin::class, 'jk_id');
    }
}
