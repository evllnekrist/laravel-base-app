<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberStatus extends Model
{
    protected $table = 'ms_member_status';

    public function member(){
        return $this->belongsTo('App\Http\Models\Member','status_code');
    }
}
