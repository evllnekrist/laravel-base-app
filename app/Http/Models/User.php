<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'ms_user';

    public function role(){
        return $this->hasOne('App\Http\Models\Role','foreign_key');
    }

    public function company(){
        return $this->hasOne('App\Http\Models\Company');
    }
}
