<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanObatModel extends Model
{
    use HasFactory;

    protected $table = 'penjualan_obat';
    protected $fillable = [
        'no_faktur',
        'jumlah_beli',
        'ppn',
        'pph',
        'diskon',
        'harga_beli_kotor',
        'harga_beli_bersih'
    ];

    // Relasi ke detail penjualan
    public function detail()
    {
        return $this->hasMany(DetailPenjualanObatModel::class, 'penjualan_id');
    }
}
