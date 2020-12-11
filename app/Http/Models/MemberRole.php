<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberRole extends Model
{
    protected $table = 'ms_member_role';

    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'member_role_id');
    }
}
