<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'ms_member';

    public function role(){
        return $this->hasOne('App\Models\MemberRole','foreign_key');
    }

    public function package(){
        return $this->hasOne('App\Models\Package','foreign_key');
    }
}
