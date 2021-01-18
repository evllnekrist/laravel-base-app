<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'ms_ab_districts';

    public function regency(){
        return $this->hasOne('App\Http\Models\Regency');
    }
}
