<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\User;
use App\Http\Models\Role;
use App\Http\Models\MemberRole;
use App\Http\Models\Active;
use DB;

class MemberRoleController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._data.member-role');
    }
    
    public function index(Request $request){
        return view('_page._data.index-member-role',$this->data);
    }
    
    public function get(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'name',
            2 =>'color_sign',
            3 =>'active',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // DB::enableQueryLog(); // Enable query log
        $models =  DB::table('ms_member_role')
                        ->select('*')->orderBy('created_at','DESC');
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('name','LIKE',"%{$search}%")->orderBy('created_at','DESC');
                    });
        }
        // dd(DB::getQueryLog()); // Show results of log
        $recordsFiltered = $models->orderBy($order,$dir)->get()->count();        
        $recordsTotal = MemberRole::count(); // where('active','=',1)

        $models = $models->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $data = array();
        if(!empty($models)) {
            
            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->name;
                $nestedData[] = $model->color_sign;
                $nestedData[] = ($model->active? '<i class="feather icon-check ft-blue-band"></i>':'');
                $action = '';
                if($this->data['authorize']['edit']==1){
                    $action .=   "   <span class='action-edit' data-hash='".md5($model->id)."' data-title=''>
                                        <i class='feather icon-edit'></i>
                                    </span>";
                }
                if($this->data['authorize']['delete']==1){
                    $action .=   "   <span class='action-delete' data-hash='".md5($model->id)."' data-title=''>
                                        <i class='feather icon-trash'></i>
                                    </span>";
                }
                $nestedData[] = $action;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => $data
        );
        
        return json_encode($json_data);
    }

    public function doAdd(Request $request){
        date_default_timezone_set("Asia/Jakarta");

        unset($request['_token']);
        $item = $request->get('params');
        $item['created_by'] = \Session::get('_user')['_id'];
        $msg = 'to add Member Role <b>'.$item['name'].'</b>';

        try{
            MemberRole::insertGetId($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>json_encode($e));
        }

        AppLog::createLog('add member-role',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){
        $item = MemberRole::where(DB::raw('md5(id)'),'=',$id)->first();

        $data = array(
            "detail"=>$item,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        
        unset($request['_token']);
        $item = $request->get('params');
        $item['updated_by'] = \Session::get('_user')['_id'];
        $id = $item['id'];
        unset($item['id']);
        $msg = 'to edit Member Role <b>'.$item['name'].'</b>';

        try{
            MemberRole::where(DB::raw('md5(id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>json_encode($e));
        }

        AppLog::createLog('edit member-role',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete Member Role';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                MemberRole::where(DB::raw('md5(id)'),'=',$id)->delete();
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
        
        AppLog::createLog('delete member-role',$ids,$output);
        return json_encode($output);
    }
}
