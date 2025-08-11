<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPembelianObatModel extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian_obat';
    protected $fillable = [
        'pembelian_id',
        'obat_id',
        'kuantitas',
        'harga_beli',
        'jumlah',
        'kadaluarsa'
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianObatModel::class, 'pembelian_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
