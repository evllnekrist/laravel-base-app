<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'ms_member';
    protected $fillable = [
                            'card_id',
                            'card_print_count',
                            'status_code',
                            'first_name',
                            'last_name',
                            'ktp_number',
                            'ktp_file',
                            'pob',
                            'dob',
                            'gender_code',
                            'member_role_id',
                            'email',
                            'phone',
                            'address',
                            'province',
                            'city',
                            'post_code',
                            'active'
                        ];

    public function status(){
        return $this->hasOne('App\Http\Models\MemberStatus','code','status_code');
    }
    public function gender(){
        return $this->hasOne('App\Http\Models\Gender','code','gender_code');
    }
    public function role(){
        return $this->hasOne('App\Http\Models\MemberRole','id','member_role_id');
    }
}
