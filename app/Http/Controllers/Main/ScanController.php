<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\Member;
use App\Http\Models\MemberActivity;
use App\Http\Models\MemberTransaction;
use Illuminate\Support\Facades\View;
use DB;

class ScanController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('.scan');
    }

    public function index(Request $request){
        return view('_page._main.index-scan',$this->data);
    }
    
    public function get(Request $request){
        // DB::enableQueryLog();
        // dd(DB::getQueryLog());
        // ->skip(0)->take(50)
        $data = MemberActivity::whereDate('created_at', DB::raw('CURDATE()'))->with('transaction')->orderBy('id', 'DESC')->get();
        return array(
            "list_data" => $data,
        );
    }

    public function doAdd(Request $request){
        
        if($request->ajax()) {
            $item = $request->all();
            $item['created_by'] = \Session::get('_user')['_id'];
            $msg = 'to add activity-scan';

            $detail = Member::where('card_id', $item["card_id"])->with('status')->with('gender')->with('role')->first();
            if ($detail) {
                try{
                    // >> in case IN & OUT are necessary
                    // $last_activity = MemberActivity::where('card_id','=',$item["card_id"])->orderBy('id', 'DESC')->value('transaction_code');
                    // $item['transaction_code'] = ($last_activity!='attend_in'?'attend_in':'attend_out'); 
                    // >> other.
                    $item['transaction_code'] = 'attend';
                    $id = MemberActivity::insertGetId($item);
                    $output = array('status'=>true, 'message'=>'Success '.$msg, 'detail'=>$detail);
                }catch(\Exception $e){
                    $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
                }
            }else{
                $output = array('status'=>false, 'message'=>' Card Id NOT EXIST');
            }
        }else{
            $output = array('status'=>false, 'message'=>' Request invalid');
        }

        AppLog::createLog('add member',$item,$output);
        return $output;
    }

}
