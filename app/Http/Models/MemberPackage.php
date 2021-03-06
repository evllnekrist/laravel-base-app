<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPackage extends Model
{
    protected $table = 'ms_member_package';
    protected $fillable = [
                            'card_id',
                            'package_id',
                            'activity_id',
                            'start_at',
                            'end_at',
                            'created_by',
                            'updated_by'
                        ];

    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'card_id');
    }
    public function package(){
        return $this->hasOne('App\Http\Models\Package','id','package_id');
    }
}
