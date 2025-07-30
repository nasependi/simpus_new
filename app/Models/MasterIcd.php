<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterIcd extends Model
{
    protected $table = 'icds';
    protected $guarded = [];
    public $timestamps = false;
}
