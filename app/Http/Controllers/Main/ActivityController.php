<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\MemberActivity;
use App\Http\Models\MemberTransaction;
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
        $data = MemberActivity::with('transaction')->get();
        return array(
            "list_data" => $data,
        );
    }

    public function detailAdd(){
        $list_transaction = MemberTransaction::get();

        $data = array(
            "list_transaction"=>$list_transaction,
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
        $item = Member::where('id','=',$id)->with('status')->with('gender')->with('role')->first();
        $list_role = MemberRole::where('active','=',1)->get();
        $list_status = MemberStatus::where('active','=',1)->get();
        $list_gender = Gender::where('active','=',1)->get();

        $data = array(
            "hash" => md5($id),
            "selected_data"=>$item,
            "list_role"=>$list_role,
            "list_status"=>$list_status,
            "list_gender"=>$list_gender,
        );
        
        return View::make('_page._main.detail-membership', $data);
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
