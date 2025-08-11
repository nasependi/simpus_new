<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianObatModel extends Model
{
    use HasFactory;

    protected $table = 'pembelian_obat';
    protected $fillable = [
        'no_faktur',
        'jumlah_beli',
        'ppn',
        'pph',
        'diskon',
        'harga_beli_kotor',
        'harga_beli_bersih'
    ];

    // Relasi ke detail pembelian
    public function detail()
    {
        return $this->hasMany(DetailPembelianObatModel::class, 'pembelian_id');
    }
}
