<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTransaction extends Model
{
    protected $table = 'ms_member_transaction';

    public function member(){
        return $this->belongsTo('App\Http\Models\MemberActivity', 'code');
    }
}
