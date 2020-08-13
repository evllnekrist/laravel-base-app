<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'ms_users';

    public function role(){
        return $this->hasOne('App\Models\Role','foreign_key');
    }

    public function company(){
        return $this->hasOne('App\Models\Company');
    }
}
