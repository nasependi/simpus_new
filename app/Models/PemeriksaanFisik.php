<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanFisik extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_fisik';

    protected $fillable = [
        'kunjungan_id',
        'tingkatkesadaran_id',
        'gambar_anatomitubuh',
        'denyut_jantung',
        'pernapasan',
        'sistole',
        'diastole',
        'suhu_tubuh',
        'kepala',
        'mata',
        'telinga',
        'hidung',
        'rambut',
        'bibir',
        'gigi_geligi',
        'lidah',
        'langit_langit',
        'leher',
        'tenggorokan',
        'tonsil',
        'dada',
        'payudara',
        'punggung',
        'perut',
        'genital',
        'anus',
        'lengan_atas',
        'lengan_bawah',
        'kuku_tangan',
        'persendian_tangan',
        'tungkai_atas',
        'tungkai_bawah',
        'jari_kaki',
        'kuku_kaki',
        'persendian_kaki',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function tingkatKesadaran()
    {
        return $this->belongsTo(TingkatKesadaran::class, 'tingkatkesadaran_id');
    }
}

