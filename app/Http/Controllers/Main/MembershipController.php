<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Models\AppLog;
use App\Http\Models\Member;
use App\Http\Models\MemberActivity;
use App\Http\Models\MemberPackage;
use App\Http\Models\MemberRole;
use App\Http\Models\MemberStatus;
use App\Http\Models\Package;
use App\Http\Models\Gender;
use App\Http\Models\Site;
use App\Http\Models\Active;
use App\Http\Models\AB_Province;
use App\Http\Models\AB_Regency;
use App\Http\Models\AB_District;
use App\Http\Models\AB_Village;
use Illuminate\Support\Facades\View;
use Anouar\Fpdf\Fpdf;
use DB;

class MembershipController extends Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->data['header_data']['js'] = array('.membership');
    }
    
    public function index(Request $request){
        $this->data['list_role'] = MemberRole::where('active','=',1)->get();
        $this->data['list_status'] = MemberStatus::where('active','=',1)->get();
        $this->data['list_gender'] = Gender::where('active','=',1)->get();
        $this->data['list_province'] = AB_Province::orderBy('name', 'ASC')->get();
        $this->data['list_site'] = Site::where('active','=',1)->get();
        return view('_page._main.index-membership', $this->data);
    }
    
    public function get(Request $request){
        // DB::enableQueryLog();
        // dd(DB::getQueryLog());
        $data = Member::select('ms_member.*','ms_member_package.start_at','ms_member_package.end_at','ms_package.name as package_name','ms_package.site_code')
                        ->with('status')->with('gender')->with('role')->with('province')->with('regency')->with('district')->with('village')
                        ->leftJoin('ms_member_package','ms_member.card_id','=','ms_member_package.card_id')
                        ->leftJoin('ms_package','ms_member_package.package_id','=','ms_package.id')
                        ->orderBy('created_at','DESC')->get();
        return array(
            "list_data" => $data,
        );
    }

    public function detailAdd(){
        $list_role = Role::where('active','=',1)->get();
        $list_company = Company::where('active','=',1)->get();

        $data = array(
            "list_role"=>$list_role,
            "list_company"=>$list_company,
        );
        
        return response()->json($data);
    }

    public function doAdd(Request $request){
        date_default_timezone_set("Asia/Jakarta");

        if($request->ajax()) {
            $item = $request->all();
            $item['created_by'] = \Session::get('_user')['_id'];
            $item['created_at'] = date('Y-m-d h:i:s');
            $msg = 'to add new member';

            if (Member::where('ktp_number', $item["ktp_number"])->exists()) {
                $detail = Member::where('ktp_number', $item["ktp_number"])->get();
                $output = array('status'=>false, 'message'=>'KTP <b><u>'.$item['ktp_number'].'</u></b> has been used '.$msg, 'detail'=>$detail);
            }else if(Member::where('email', $item["email"])->exists()){
                $detail = Member::where('email', $item["email"])->get();
                $output = array('status'=>false, 'message'=>'Email <b><u>'.$item['email'].'</u></b> has been used '.$msg, 'detail'=>$detail);
            }else if(Member::where('phone', $item["phone"])->exists()){
                $detail = Member::where('phone', $item["phone"])->get();
                $output = array('status'=>false, 'message'=>'Phone Number <b><u>'.$item['phone'].'</u></b> has been used '.$msg, 'detail'=>$detail);
            }else{
                try{
                    $item['card_id'] = Session::get('_user')['_company'].($item['member_role_id']>9?$item['member_role_id']:'0'.$item['member_role_id']).date("y").date("m");
                    $latestMember = Member::where('card_id', 'like', $item['card_id'].'%')->max('card_id');
                    
                    if($latestMember){
                        $item['card_id'] = strval($latestMember + 1);
                    }else{
                        $item['card_id'] = $item['card_id'].'0001';
                    }

                    if($item['member_role_id'] == 1){ // member
                        $end_date = strtotime($item['end_at']);
                        $today_date = strtotime(date('Y-m-d'));
                        if($end_date < $today_date){
                            $item['status_code'] = 'exp';
                        }

                        $itemPackage = array(
                            "card_id" => $item['card_id'],
                            "package_id" => $item['package_id'],
                            "start_at" => $item['start_at'],
                            "end_at" => $item['end_at'],
                            "created_by" => $item['created_by'],
                            "created_at" =>$item['created_at']
                        );
                        $itemActivity = array(
                            "card_id" => $item['card_id'],
                            "transaction_code" => 'subs_new',
                            "detail" => json_encode($itemPackage),
                            "created_by" => Session::get('_user')['_id'],
                            "created_at" =>$item['created_at']
                        );
                        $idActivity = MemberActivity::insertGetId($itemActivity);
                        $itemPackage['activity_id'] = $idActivity;
                        $idPackage = MemberPackage::insertGetId($itemPackage);
                    }
                    
                    // unset($item['status_code_selected']);
                    unset($item['site_code']);
                    unset($item['package_id']);
                    unset($item['start_at']);
                    unset($item['end_at']);
                    $id = Member::insertGetId($item);
                    $output = array('status'=>true, 'message'=>'Success '.$msg, 'detail'=>$id);
                }catch(\Exception $e){
                    $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e);
                }
            }
        }else{
            $output = array('status'=>false, 'message'=>' Request invalid');
        }

        AppLog::createLog('add member',$item,$output);
        return $output;
    }

    public function detailEdit($id){
        $this->data['selected_data'] = Member::where('id','=',$id)->with('status')->with('gender')->with('role')->with('province')->with('regency')->with('district')->with('village')->first();
        $this->data['list_status'] = MemberStatus::where('active','=',1)->where('role_id','=',$this->data['selected_data']['member_role_id'])->get();
        $this->data['list_role'] = MemberRole::where('active','=',1)->get();
        $this->data['list_gender'] = Gender::where('active','=',1)->get();
        $this->data['list_site'] = Site::where('active','=',1)->get();
        $this->data['hash'] =  md5($id);

        $this->data['list_province'] = AB_Province::orderBy('name', 'ASC')->get();
        $this->data['list_regency'] = []; $this->data['list_district'] = []; $this->data['list_village'] = [];
        if($this->data['selected_data']['province_id']){
            $this->data['list_regency'] = AB_Regency::where('province_id','=',$this->data['selected_data']['province_id'])->orderBy('name', 'ASC')->get();
        }
        if($this->data['selected_data']['regency_id']){
            $this->data['list_district'] = AB_District::where('regency_id','=',$this->data['selected_data']['regency_id'])->orderBy('name', 'ASC')->get();
        }
        if($this->data['selected_data']['district_id']){
            $this->data['list_village'] = AB_Village::where('district_id','=',$this->data['selected_data']['district_id'])->orderBy('name', 'ASC')->get();
        }

        if($this->data['selected_data']['member_role_id'] == 1){ // member
            $this->data['selected_data_subs'] = MemberPackage::where('card_id','=',$this->data['selected_data']['card_id'])->with('package')->first();  
            $subsPackageDetail = Package::where('active','=',1)->where('id','=',$this->data['selected_data_subs']['package_id'])->first();      
            $this->data['list_package'] = Package::where('active','=',1)->where('site_code','=',$subsPackageDetail['site_code'])->get();
        }
        
        // dd($this->data);
        return View::make('_page._main.detail-membership', $this->data);
    }

    public function doEdit(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        
        $item = $request->all();
        $item['updated_by'] = \Session::get('_user')['_id'];
        $item['updated_at'] = date('Y-m-d h:i:s');
        $id = $item['id'];
        unset($item['id']);
        $msg = 'to edit member '.$item['first_name'].' '.$item['last_name'];
        // dump($item);
        try{
            if($item['member_role_id'] == 1){ // member
                
                $subsActivity = true;
                $itemPackage = array();
                $lastPackage = MemberPackage::where('card_id','=',$item['card_id'])->first(); 
                $today_date = strtotime(date('Y-m-d'));
                
                if(!$lastPackage){ // new
                    $transactionCode = 'subs_new';
                    $itemPackage['created_by'] = $item['updated_by'];
                    $end_date = strtotime($item['end_at']);
                    if($end_date < $today_date){
                        $item['status_code'] = 'exp';
                    }
                }else{ // exist
                    $prevStartAt = explode(" ",$lastPackage->start_at);
                    $prevEndAt = explode(" ",$lastPackage->end_at);
                    if($prevStartAt[0] == $item['start_at'] && $lastPackage->package_id == $item['package_id']){ // nothing change
                        $subsActivity = false;
                    }else if($prevEndAt[0] == $item['start_at'] && $lastPackage->package_id == $item['package_id']){ // same package, extend date
                        $transactionCode = 'subs_ext';
                    }else{
                        $transactionCode = 'subs_new';
                    }
                    $itemPackage['updated_by'] = $item['updated_by'];
                    // dump($lastPackage->start_at,$item['start_at'],$lastPackage->package_id,$item['package_id'],$lastPackage->end_at,$item['end_at']);
                       
                    $end_date = strtotime($lastPackage->end_at);
                    $today_date = strtotime(date('Y-m-d'));
                    if($end_date < $today_date){
                        $item['status_code'] = 'exp';
                    }
                }

                if($subsActivity){
                    $itemPackage = array_merge(
                        $itemPackage, array(
                            "card_id" => $item['card_id'],
                            "package_id" => $item['package_id'],
                            "start_at" => $item['start_at'],
                            "end_at" => $item['end_at'],
                        )
                    );
                    $itemActivity = array(
                        "card_id" => $item['card_id'],
                        "transaction_code" => $transactionCode,
                        "detail" => json_encode($itemPackage),
                        "created_by" => Session::get('_user')['_id'],
                        "created_at" => date('Y-m-d h:i:s')
                    );
                    $idActivity = MemberActivity::insertGetId($itemActivity);
                    $itemPackage['activity_id'] = $idActivity;
                    // DB::enableQueryLog();
                    $idPackage = MemberPackage::updateOrCreate(array("card_id" => $item['card_id']),$itemPackage);
                    // dd(DB::getQueryLog());
                }
            }
            // unset($item['status_code_selected']);
            unset($item['site_code']);
            unset($item['package_id']);
            unset($item['start_at']);
            unset($item['end_at']);
            unset($item['card_id']);
            Member::where(DB::raw('md5(id)'),'=',$id)->update($item);
            
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e);
        }

        AppLog::createLog('edit member',$item,$output);
        return $output;
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete member';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                Member::where(DB::raw('md5(id)'),'=',$id)->delete();
                $deletedRows++;
            }
            
            if($deletedRows >= 1){
                $s = ($deletedRows > 1) ? "'s" : "";
                $output = array('status'=>true, 'message'=>'Success '.$msg.$s.' ['.$deletedRows.' row'.$s.']');
            }else{
                $output = array('status'=>false, 'message'=>'Selected data unavailable in database', 'detail'=>'');
            }
        }catch(Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>json_encode($e));
        }
        
        AppLog::createLog('delete user',$ids,$output);
        return json_encode($output);
    }
}
