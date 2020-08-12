<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'ms_roles';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
