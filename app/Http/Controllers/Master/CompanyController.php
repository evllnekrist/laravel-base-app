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

class CompanyController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._data.company');
    }
    
    public function index(Request $request){
        return view('_page._data.index-company',$this->data);
    }
    
    public function get(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'code',
            2 =>'name',
            3 =>'active',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // DB::enableQueryLog(); // Enable query log
        $models =  DB::table('ms_company')
                        ->select('*')->orderBy('created_at','DESC');
                        // ->leftJoin('ms_role as r', 'u.role_id', '=', 'r.id')
                        // ->leftJoin('ms_company as c', 'u.company_id', '=', 'c.id');
                        // ->where('u.active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('name','LIKE',"%{$search}%")->orderBy('created_at','DESC');
                    });
        }
        // dd(DB::getQueryLog()); // Show results of log
        $recordsFiltered = $models->orderBy($order,$dir)->get()->count();        
        $recordsTotal = Company::count(); // where('active','=',1)

        $models = $models->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $data = array();
        if(!empty($models)) {
            
            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->code;
                $nestedData[] = $model->name;
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

    // public function detailAdd(){
    //     $list_role = Role::where('active','=',1)->get();
    //     $list_company = Company::where('active','=',1)->get();

    //     $data = array(
    //         "list_role"=>$list_role,
    //         "list_company"=>$list_company,
    //     );
        
    //     return response()->json($data);
    // }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['created_by'] = \Session::get('_user')['_id'];
        $item['created_at'] = date('Y-m-d h:i:s');
        $msg = 'to add company <b>'.$item['name'].'</b>';
        
        try{
            Company::insertGetId($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>json_encode($e));
        }

        AppLog::createLog('add company',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){
        $item = Company::where(DB::raw('md5(id)'),'=',$id)->first();

        $data = array(
            "detail"=>$item,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['updated_by'] = \Session::get('_user')['_id'];
        $item['updated_at'] = date('Y-m-d h:i:s');
        $id = $item['id'];
        unset($item['id']);
        $msg = 'to edit Company <b>'.$item['name'].'</b>';

        try{
            Company::where(DB::raw('md5(id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>json_encode($e));
        }

        AppLog::createLog('edit company',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete company';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                Company::where(DB::raw('md5(id)'),'=',$id)->delete();
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
        
        AppLog::createLog('delete company',$ids,$output);
        return json_encode($output);
    }
}
