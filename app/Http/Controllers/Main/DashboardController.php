<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\Member;
use App\Http\Models\MemberActivity;
// use App\Http\Models\MemberPackage;
use Illuminate\Support\Facades\View;
use DB;

class DashboardController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		// $this->data['header_data']['js'] = array('.dashboard');
    }

    public function index(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        // -------------------------------COUNT Member-----------------------------------------------------------
        try{
            $this->data['member']['sum']    = Member::where('active','=',1)
                                                    ->where('member_role_id','=',1)->count();
            $member_a_year_init             = array_fill(0,12,0);
            $member_a_year                  = Member::selectRaw("
                                                            EXTRACT(MONTH FROM created_at)-1 AS month,
                                                            count(id) AS value")
                                                    ->where('active','=',1)
                                                    ->where('member_role_id','=',1)
                                                    ->whereYear("created_at",date("Y"))
                                                    ->groupBy('month')
                                                    ->get()
                                                    ->pluck("value","month");
            $this->data['member']['a_year'] = json_encode(array_replace($member_a_year_init,$member_a_year->toArray()));
        }catch(Exception $e) {
            $this->data['member']['sum']    = null;
            $this->data['member']['a_year'] = null;
        }
        // -------------------------------COUNT Personal Trainer--------------------------------------------------
        try{
            $this->data['pt']['sum']        = Member::where('active','=',1)
                                                    ->where('member_role_id','=',3)->count();
            $pt_a_year_init                 = array_fill(0,12,0);
            $pt_a_year                      = Member::selectRaw("
                                                            EXTRACT(MONTH FROM created_at)-1 AS month,
                                                            count(id) AS value")
                                                    ->where('active','=',1)
                                                    ->where('member_role_id','=',3)
                                                    ->whereYear("created_at",date("Y"))
                                                    ->groupBy('month')
                                                    ->get()
                                                    ->pluck("value","month");
            $this->data['pt']['a_year']     = json_encode(array_replace($pt_a_year_init,$pt_a_year->toArray()));
        }catch(Exception $e) {
            $this->data['pt']['sum']        = null;
            $this->data['pt']['a_year']     = null;
        }
        // -------------------------------COUNT Staff-------------------------------------------------------------
        try{
            $this->data['staff']['sum']    = Member::where('active','=',1)
                                                    ->where('member_role_id','=',2)->count();
            $staff_a_year_init             = array_fill(0,12,0);
            $staff_a_year                  = Member::selectRaw("
                                                            EXTRACT(MONTH FROM created_at)-1 AS month,
                                                            count(id) AS value")
                                                    ->where('active','=',1)
                                                    ->where('member_role_id','=',2)
                                                    ->whereYear("created_at",date("Y"))
                                                    ->groupBy('month')
                                                    ->get()
                                                    ->pluck("value","month");
            $this->data['staff']['a_year'] = json_encode(array_replace($staff_a_year_init,$staff_a_year->toArray()));
        }catch(Exception $e) {
            $this->data['staff']['sum']    = null;
            $this->data['staff']['a_year'] = null;
        }
        // -------------------------------ABSENCE-----------------------------------------------------------------
        try{
            $this->data['card']['sum']      = $this->data['member']['sum']+$this->data['pt']['sum']+$this->data['staff']['sum'];
            $this->data['card']['absence']  = MemberActivity::whereDate('created_at', DB::raw('CURDATE()'))
                                            ->where('transaction_code','like','attend'.'%')
                                            ->count(DB::raw('DISTINCT card_id'));
        }catch(Exception $e) {
            $this->data['card']['sum']      = null;
            $this->data['card']['absence']  = null;
        }
        // -------------------------------SUBSCRIPTION------------------------------------------------------------
        try{
            $sub                            = Member::selectRaw("
                                                        status_code,
                                                        count(id) as sum")
                                                    ->where('active','=',1)
                                                    ->where('member_role_id','=',1)
                                                    ->groupBy('status_code')
                                                    ->get()->groupBy('status_code')->toArray();
            if($sub){
                $this->data['sub']['sum']       = json_encode(array(
                                                    array_key_exists('exp',$sub)?intval($sub['exp'][0]['sum']):0,
                                                    array_key_exists('sub',$sub)?intval($sub['sub'][0]['sum']):0
                                                ));
            }else{
                $this->data['sub']['sum']       = null;
            }
        }catch(Exception $e) {
            $this->data['sub']['sum']       = null;
        }
        // -------------------------------------------------------------------------------------------------------||--DONE

        // dd($this->data);
        return view('_page._main.index-dashboard',$this->data);
    }

}
