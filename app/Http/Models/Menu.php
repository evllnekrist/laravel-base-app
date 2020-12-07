<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'ms_menu';

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    // public function parent()
    // {
    //     return $this->belongsTo(self::class, 'parent');
    // }

    // public function children()
    // {
    //     return $this->hasMany(self::class, 'parent');
    // }

}
