<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\User;
use App\Http\Models\Role;
use App\Http\Models\Company;
use App\Http\Models\Active;
use DB;

class UsersController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._app.users');
    }
    
    public function index(Request $request){
        return view('_page._app.index-users',$this->data);
    }
    
    public function get(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'username',
            2 =>'fullname',
            3 =>'active',
            6 =>'role_name',
            5 =>'company_name', 
            6 =>'email',
            7 =>'phone',
            8 =>'address'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // DB::enableQueryLog(); // Enable query log
        $models =  DB::table('ms_users as u')
                        ->select('u.id','u.username','u.fullname','u.email','u.phone','u.address','u.active',
                        'r.name as role_name','c.name as company_name')
                        ->leftJoin('ms_roles as r', 'u.role_id', '=', 'r.id')
                        ->leftJoin('ms_company as c', 'u.company_id', '=', 'c.id');
                        // ->where('u.active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('username','LIKE',"%{$search}%")
                                ->orWhere('fullname', 'LIKE',"%{$search}%")
                                ->orWhere('email', 'LIKE',"%{$search}%")
                                ->orWhere('phone', 'LIKE',"%{$search}%")
                                ->orWhere('address', 'LIKE',"%{$search}%")
                                ->orWhere('role_name', 'LIKE',"%{$search}%")
                                ->orWhere('company_name', 'LIKE',"%{$search}%");
                    });
        }
        $models = $models->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        // dd(DB::getQueryLog()); // Show results of log

        $recordsFiltered = count(get_object_vars($models));        
        $recordsTotal = User::count(); // where('active','=',1)

        $data = array();
        if(!empty($models)) {
            
            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->username;
                $nestedData[] = $model->fullname;
                $nestedData[] = ($model->active? '<i class="feather icon-check ft-blue-band"></i>':'');
                $nestedData[] = $model->role_name;
                $nestedData[] = $model->company_name; 
                $nestedData[] = $model->email;
                $nestedData[] = $model->phone;
                $nestedData[] = $model->address;
                $action= "
                    <span class='action-edit' data-hash='".md5($model->id)."' data-title=''>
                        <i class='feather icon-edit'></i>
                    </span>
                    <span class='action-delete' data-hash='".md5($model->id)."' data-title=''>
                        <i class='feather icon-trash'></i>
                    </span>
                ";
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
        unset($request['_token']);
        $item = $request->get('params');
        $item['password'] = md5(md5($item['password']));
        $msg = 'to add new user <b>'.$item['fullname'].'</b>';

        try{
            User::insertGetId($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('add user',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){
        $item = User::where(DB::raw('md5(id)'),'=',$id)->first();
        $list_role = Role::where('active','=',1)->get();
        $list_company = Company::where('active','=',1)->get();

        $data = array(
            "detail"=>$item,
            "list_role"=>$list_role,
            "list_company"=>$list_company,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $id = $item['id'];
        unset($item['id']);
        $msg = 'to edit user <b>'.$item['fullname'].'</b>';

        try{
            User::where(DB::raw('md5(id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('edit user',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete user';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                User::where(DB::raw('md5(id)'),'=',$id)->delete();
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
