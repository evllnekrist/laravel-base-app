<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'ms_gender';

    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'gender_code');
    }
}
