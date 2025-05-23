<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaraPembayaran extends Model
{
    protected $table ="cara_pembayaran";
    protected $fillable = ['nama', 'keterangan', 'status'];
}
