<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\MemberActivity;
use App\Http\Models\MemberTransaction;
use App\Http\Models\Package;
use Illuminate\Support\Facades\View;
use DB;

class ActivityController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('.activity');
    }
    
    public function index(Request $request){
        $this->data['list_transaction'] = MemberTransaction::get();
        return view('_page._main.index-activity', $this->data);
    }
    
    public function get(Request $request){
        // DB::enableQueryLog();
        // dd(DB::getQueryLog());
        $data = MemberActivity::with('member')->with('transaction')->with('user')->orderBy('created_at','DESC')->get();
        return array(
            "list_data" => $data,
        );
    }

    public function detailEdit($id){
        $this->data['hash'] =  md5($id);
        $this->data['selected_data'] = MemberActivity::where('id','=',$id)->with('member')->with('transaction')->with('user')->first();
        if($this->data['selected_data']['detail'] && str_contains($this->data['selected_data']['transaction_code'], 'subs')){
            $detail = json_decode($this->data['selected_data']['detail']);
            $package = Package::where('id','=',$detail->package_id)->first();
            $this->data['detail'] = array(
                    'code' => $package->code,
                    'name' => $package->name,
                    'site' => $package->site_code,
                    'start_at'  => $detail->start_at,
                    'end_at'    => $detail->end_at,

                );
        }
        
        return View::make('_page._main.detail-activity', $this->data);
    }
}
