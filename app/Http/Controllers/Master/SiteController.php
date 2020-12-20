<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\Site;
use App\Http\Models\Company;
use App\Http\Models\Active;
use DB;

class SiteController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._data.site');
    }
    
    public function index(Request $request){
        return view('_page._data.index-site',$this->data);
    }
    
    public function get(Request $request){

        $columns = array(
            0 =>'code',
            1 =>'code',
            2 =>'name',
            3 =>'active',
            4 =>'company_name',
            5 =>'email',
            6 =>'phone',
            7 =>'address'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // DB::enableQueryLog(); // Enable query log
        $models =  DB::table('ms_site as s')
                        ->select('s.code','s.name', 's.company_code', 's.email', 's.phone', 's.address', 's.active', 's.manager',
                        'c.name as company_name')
                        ->leftJoin('ms_company as c', 's.company_code', '=', 'c.code');
                        // ->where('u.active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('name','LIKE',"%{$search}%")
                                ->orWhere('email', 'LIKE',"%{$search}%")
                                ->orWhere('phone', 'LIKE',"%{$search}%")
                                ->orWhere('address', 'LIKE',"%{$search}%")
                                ->orWhere('company_name', 'LIKE',"%{$search}%");
                    });
        }
        $models = $models->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        // dd(DB::getQueryLog()); // Show results of log

        $recordsFiltered = count(get_object_vars($models));        
        $recordsTotal = Site::count(); // where('active','=',1)

        $data = array();
        if(!empty($models)) {
            
            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->code;
                $nestedData[] = $model->name;
                $nestedData[] = ($model->active? '<i class="feather icon-check ft-blue-band"></i>':'');
                $nestedData[] = $model->company_name; 
                $nestedData[] = $model->email;
                $nestedData[] = $model->phone;
                $nestedData[] = $model->address;
                $action= "
                    <span class='action-edit' data-hash='".md5($model->code)."' data-title=''>
                        <i class='feather icon-edit'></i>
                    </span>
                    <span class='action-delete' data-hash='".md5($model->code)."' data-title=''>
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
        $list_company = Company::where('active','=',1)->get();

        $data = array(
            "list_company"=>$list_company,
        );
        
        return response()->json($data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['created_by'] = \Session::get('_user')['_id'];
        $msg = 'to add site <b>'.$item['name'].'</b>';
        
        try{
            Site::insert($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('add site',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){
        $item = Site::where(DB::raw('md5(code)'),'=',$id)->first();
        $list_company = Company::where('active','=',1)->get();

        $data = array(
            "detail"=>$item,
            "list_company"=>$list_company,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['updated_by'] = \Session::get('_user')['_id'];
        $id = $item['id'];
        // var_dump($item);exit;
        unset($item['id']);
        $msg = 'to edit site <b>'.$item['name'].'</b>';

        try{
            Site::where(DB::raw('md5(code)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('edit site',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete site';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                Site::where(DB::raw('md5(code)'),'=',$id)->delete();
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
        
        AppLog::createLog('delete site',$ids,$output);
        return json_encode($output);
    }
}
