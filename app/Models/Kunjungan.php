<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $fillable = [
        'pasien_id',
        'umur_tahun',
        'umur_bulan',
        'umur_hari',
        'poli_id',
        'tanggal_kunjungan',
        'carapembayaran_id',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function pasien()
    {
        return $this->belongsTo(PasienUmum::class, 'pasien_id');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli_id');
    }

    public function obatResep()
    {
        return $this->hasMany(ObatResep::class, 'kunjungan_id');
    }


    public function caraPembayaran()
    {
        return $this->belongsTo(CaraPembayaran::class, 'carapembayaran_id');
    }
    public function generalConsent()
    {
        return $this->hasOne(GeneralConsent::class);
    }
}
