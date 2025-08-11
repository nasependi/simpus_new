<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'golongan',
        'sediaan',
    ];

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelianObatModel::class, 'obat_id');
    }

    // Relasi ke detail penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualanObatModel::class, 'obat_id');
    }
}
