<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPenjualanObatModel extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan_obat';
    protected $fillable = [
        'penjualan_id',
        'obat_id',
        'kuantitas',
        'harga_beli',
        'jumlah',
        'kadaluarsa'
    ];

    public function penjualan()
    {
        return $this->belongsTo(PenjualanObatModel::class, 'penjualan_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
