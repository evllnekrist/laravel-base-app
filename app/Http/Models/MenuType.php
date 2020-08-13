<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class MenuType extends Model
{
    public static function getList(){
        return array('parent','sub-parent');
    }
}
