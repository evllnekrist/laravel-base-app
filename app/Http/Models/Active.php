<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Active extends Model
{
    public static function getList(){
        return array(
            array('code' => 1, 'name' => 'active'), 
            array('code' => 0, 'name' => 'not active')
        );
    }
}
