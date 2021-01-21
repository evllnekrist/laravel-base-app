<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $table = 'ms_ab_regencies';

    public function province(){
        return $this->hasOne('App\Http\Models\Province');
    }
}
