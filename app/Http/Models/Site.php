<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'ms_site';

    public function company(){
        return $this->hasOne('App\Http\Models\Company');
    }
}
