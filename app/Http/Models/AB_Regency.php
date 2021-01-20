<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AB_Regency extends Model
{
    protected $table = 'ms_ab_regencies';
    protected $fillable = [
                            'id',
                            'province_id',
                            'name'
                        ];

    public function province(){
        return $this->belongsTo('App\Http\Models\AB_Province', 'id', 'province_id');
    }
    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'regency_id', 'id');
    }
}
