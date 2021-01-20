<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AB_Province extends Model
{
    protected $table = 'ms_ab_provinces';
    protected $fillable = [
                            'id',
                            'name'
                        ];

    public function member(){
        return $this->belongsTo('App\Http\Models\Member', 'province_id', 'id');
    }
}
