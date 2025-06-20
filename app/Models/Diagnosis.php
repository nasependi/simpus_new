<?php
// app/Models/Diagnosis.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $table = 'diagnosis';

    protected $fillable = [
        'kunjungan_id',
        'diagnosis_awal',
        'diagnosis_primer',
        'diagnosis_sekunder',
    ];
}
