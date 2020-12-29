<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberActivity extends Model
{
    protected $table = 'tr_member_activity';

    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'card_id');
    }
    public function transaction(){
        return $this->hasOne('App\Http\Models\MemberTransaction','code','transaction_code');
    }
}
