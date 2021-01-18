<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'ms_ab_villages';

    public function district(){
        return $this->hasOne('App\Http\Models\District');
    }
}
