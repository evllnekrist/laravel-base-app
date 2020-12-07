<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'ms_role';

    public function user(){
        return $this->belongsTo('App\Http\Models\User');
    }
}
