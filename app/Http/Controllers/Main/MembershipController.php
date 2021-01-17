<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\Member;
use App\Http\Models\MemberRole;
use App\Http\Models\MemberStatus;
use App\Http\Models\Gender;
use App\Http\Models\Package;
use App\Http\Models\Active;
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
        return view('_page._main.index-membership', $this->data);
    }
    
    public function get(Request $request){
        // DB::enableQueryLog();
        // dd(DB::getQueryLog());
        $data = Member::with('status')->with('gender')->with('role')->get();
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
        
        if($request->ajax()) {
            $item = $request->all();
            $item['created_by'] = \Session::get('_user')['_id'];
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
                    $id = Member::insertGetId($item);
                    $output = array('status'=>true, 'message'=>'Success '.$msg, 'detail'=>$id);
                }catch(\Exception $e){
                    $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
                }
            }
        }else{
            $output = array('status'=>false, 'message'=>' Request invalid');
        }

        AppLog::createLog('add member',$item,$output);
        return $output;
    }

    public function detailEdit($id){
        $this->data['selected_data'] = Member::where('id','=',$id)->with('status')->with('gender')->with('role')->first();
        $this->data['list_role'] = MemberRole::where('active','=',1)->get();
        $this->data['list_status'] = MemberStatus::where('active','=',1)->get();
        $this->data['list_gender'] = Gender::where('active','=',1)->get();
        $this->data['hash'] =  md5($id);
        
        return View::make('_page._main.detail-membership', $this->data);
    }

    public function doEdit(Request $request){
        $item = $request->all();
        $item['updated_by'] = \Session::get('_user')['_id'];
        $id = $item['id'];
        unset($item['id']);
        $msg = 'to edit member '.$item['first_name'].' '.$item['last_name'];

        try{
            Member::where(DB::raw('md5(id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
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
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }
        
        AppLog::createLog('delete user',$ids,$output);
        return json_encode($output);
    }
}
