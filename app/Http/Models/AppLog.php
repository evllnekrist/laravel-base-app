<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AppLog extends Model
{
    protected $table = 'app_logs';

    public static function createLog($function,$item,$output){
        $log = array(
            'status' =>(array_key_exists('status',$output)?$output['status']:''),
            'function' =>$function,
            'request' =>json_encode($item),
            'response' =>(array_key_exists('message',$output)?$output['message']:'').(array_key_exists('detail',$output)?$output['detail']:''),
            'destination' =>url('/'),
            'source' =>\Request::ip(),
            'created_by' =>\Session::get('_user')['_id']
        );

        AppLog::insertGetId($log);
    }
}
