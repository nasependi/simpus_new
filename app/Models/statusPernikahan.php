<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class statusPernikahan extends Model
{
    protected $table = 'status_pernikahan';

    protected $fillable = ['kode', 'status'];
}