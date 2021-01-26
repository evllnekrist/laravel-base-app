<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\AB_Regency;
use App\Http\Models\AB_District;
use App\Http\Models\AB_Village;
use App\Http\Models\Package;
use Illuminate\Support\Facades\View;
use DB;

class SelectionController extends Controller
{
	// public function __construct(){
	// 	parent::__construct();
    // }
    
    // --------------------------------------------------ADMINISTRATIVE BOUNDARIES----------------------------
    public function getList_Regency(Request $request){
        try{
            if ($request->has('id')) {
                $data = AB_Regency::where('province_id','=',$request->input('id'))->orderBy('name', 'ASC')->get();
            }else{
                $data = AB_Regency::orderBy('name', 'ASC')->get();
            }
            return array('status'=>true,'message'=>'Success','detail'=>$data);
        }catch(\Exception $e){
            return array('status'=>true,'message'=>'Failed','detail'=>$e->getData());
        }
    }
    public function getList_District(Request $request){
        try{
            if ($request->has('id')) {
                $data = AB_District::where('regency_id','=',$request->input('id'))->orderBy('name', 'ASC')->get();
            }else{
                $data = AB_District::orderBy('name', 'ASC')->get();
            }
            return array('status'=>true,'message'=>'Success','detail'=>$data);
        }catch(\Exception $e){
            return array('status'=>true,'message'=>'Failed','detail'=>$e->getData());
        }
    }
    public function getList_Village(Request $request){
        try{
            if ($request->has('id')) {
                $data = AB_Village::where('district_id','=',$request->input('id'))->orderBy('name', 'ASC')->get();
            }else{
                $data = AB_Village::orderBy('name', 'ASC')->get();
            }
            return array('status'=>true,'message'=>'Success','detail'=>$data);
        }catch(\Exception $e){
            return array('status'=>true,'message'=>'Failed','detail'=>$e->getData());
        }
    }
    
    // --------------------------------------------------O.T.H.E.R----------------------------
    public function getList_Package(Request $request){
        try{
            if ($request->has('site_code')) {
                $data = Package::where('site_code','=',$request->input('site_code'))->orderBy('name', 'ASC')->get();
            }else{
                $data = Package::orderBy('name', 'ASC')->get();
            }
            return array('status'=>true,'message'=>'Success','detail'=>$data);
        }catch(\Exception $e){
            return array('status'=>true,'message'=>'Failed','detail'=>$e->getData());
        }
    }
}
