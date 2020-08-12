<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    protected $table = 'ms_role_menu';
    protected $fillable = ['role_id','menu_id','create','edit','view','delete','execute'];

    public function role(){
        return $this->hasOne('App\Models\Role','foreign_key');
    }

    public function menu(){
        return $this->hasOne('App\Models\Menu','foreign_key');
    }
}
