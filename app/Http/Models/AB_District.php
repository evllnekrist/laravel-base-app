<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AB_District extends Model
{
    protected $table = 'ms_ab_districts';
    protected $fillable = [
                            'id',
                            'regency_id',
                            'name'
                        ];

    public function regency(){
        return $this->belongsTo('App\Http\Models\AB_Regency', 'id', 'regency_id');
    }
    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'district_id', 'id');
    }
}
