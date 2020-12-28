<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'ms_package';

    public function company(){
        return $this->hasOne('App\Http\Models\Site');
    }
}
