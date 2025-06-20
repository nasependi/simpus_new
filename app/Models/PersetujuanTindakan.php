<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersetujuanTindakan extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_tindakan';

    protected $guarded = [
        'id'
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
