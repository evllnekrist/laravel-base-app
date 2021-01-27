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
                            'province_id',
                            'regency_id',
                            'district_id',
                            'village_id',
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
    public function province(){
        return $this->hasOne('App\Http\Models\AB_Province','id','province_id');
    }
    public function regency(){
        return $this->hasOne('App\Http\Models\AB_Regency','id','regency_id');
    }
    public function district(){
        return $this->hasOne('App\Http\Models\AB_District','id','district_id');
    }
    public function village(){
        return $this->hasOne('App\Http\Models\AB_Village','village_id','village_id');
    }
    public function member_package(){
        return $this->hasOne('App\Http\Models\MemberPackage','card_id','card_id');
    }
}
