<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'ms_menus';

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }
}
