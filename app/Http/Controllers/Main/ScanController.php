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
        $data = MemberActivity::whereDate('created_at', DB::raw('CURDATE()'))->with('transaction')->with('member')->orderBy('id', 'DESC')->get();
        return array(
            "list_data" => $data,
        );
    }

    public function doAdd(Request $request){
        if($request->ajax()) {
            $item = $request->all();
            $item['created_by'] = \Session::get('_user')['_id'];
            $msg = 'to add activity-scan';

            $detail = Member::select('ms_member.*','ms_member_package.start_at','ms_member_package.end_at','ms_package.name as package_name','ms_package.site_code')
                            ->with('status')->with('role')
                            ->leftJoin('ms_member_package','ms_member.card_id','=','ms_member_package.card_id')
                            ->leftJoin('ms_package','ms_member_package.package_id','=','ms_package.id')
                            ->where('ms_member.card_id','=',$item['card_id'])->first();
            if ($detail) {
                try{
                    if($detail['member_role_id'] == 1){ // member
                        $end_date = strtotime($detail['end_at']);
                        $today_date = strtotime(date('Y-m-d'));

                        // dump($detail['status_code'],$detail['end_at'],date("Y-m-d"),($end_date < $today_date));
                        if($detail['status_code'] == 'sub' && $end_date < $today_date){
                            Member::where('card_id','=',$item["card_id"])->update(array('status_code'=>'exp'));
                            $detail = Member::select('ms_member.*','ms_member_package.start_at','ms_member_package.end_at','ms_package.name as package_name','ms_package.site_code')
                                            ->with('status')->with('role')
                                            ->leftJoin('ms_member_package','ms_member.card_id','=','ms_member_package.card_id')
                                            ->leftJoin('ms_package','ms_member_package.package_id','=','ms_package.id')
                                            ->where('ms_member.card_id','=',$item['card_id'])->first();
                        }
                    }
                    // >> in case IN & OUT are necessary
                    // $last_activity = MemberActivity::where('card_id','=',$item["card_id"])->orderBy('id', 'DESC')->value('transaction_code');
                    // $item['transaction_code'] = ($last_activity!='attend_in'?'attend_in':'attend_out'); 
                    // >> other.
                    $item['transaction_code'] = 'attend';
                    $id = MemberActivity::insertGetId($item);
                    $output = array('status'=>true, 'message'=>'Success '.$msg, 'detail'=>$detail);
                }catch(\Exception $e){
                    $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e);
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
