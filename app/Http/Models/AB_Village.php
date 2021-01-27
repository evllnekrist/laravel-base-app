<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AB_Village extends Model
{
    protected $table = 'ms_ab_villages';
    protected $fillable = [
                            'village_id',
                            'district_id',
                            'name'
                        ];

    public function district(){
        return $this->belongsTo('App\Http\Models\AB_District', 'id', 'district_id');
    }
    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'village_id', 'village_id');
    }
}
