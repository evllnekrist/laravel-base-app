<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\AB_Province;
use App\Http\Models\AB_Regency;
use App\Http\Models\Active;
use DB;

class AB_RegencyController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._data.ab_regency');
    }
    
    public function index(Request $request){
        return view('_page._data.index-ab_regency',$this->data);
    }
    
    public function get(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'province_name',
            2 =>'regency_name',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // DB::enableQueryLog(); // Enable query log
        $models =  DB::table('ms_ab_regencies as r')
                        ->select('r.id','r.name as regency_name', 'p.name as province_name')
                        ->leftJoin('ms_ab_provinces as p', 'p.id', '=', 'r.province_id');
                        // ->where('u.active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('r.name','LIKE',"%{$search}%")
                                ->orWhere('p.name','LIKE',"%{$search}%");
                    });
        }
        // dd(DB::getQueryLog()); // Show results of log
        $recordsFiltered = $models->orderBy($order,$dir)->get()->count();        
        $recordsTotal = AB_Regency::count(); // where('active','=',1)

        $models = $models->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $data = array();
        if(!empty($models)) {
            
            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->id;
                $nestedData[] = $model->province_name; 
                $nestedData[] = $model->regency_name;
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

    public function detailAdd(){
        $list_province = AB_Province::get();

        $data = array(
            "list_province"=>$list_province,
        );
        
        return response()->json($data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['name'] = strtoupper($item['name']);
        // $item['created_by'] = \Session::get('_user')['_id'];
        unset($item['old_id']);
        $msg = 'to add regency <b>'.$item['name'].'</b>';
        
        try{
            AB_Regency::insert($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('add regency',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){
        $item = AB_Regency::where(DB::raw('md5(id)'),'=',$id)->first();
        $list_province = AB_Province::get();

        $data = array(
            "detail"=>$item,
            "list_province"=>$list_province,
        );
        
        return response()->json($data);
    }

    public function detailRegency($id){
        $item = AB_Regency::where('province_id','=',$id)->orderBy('id','DESC')->first();

        $data = array(
            "detail"=>$item,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['name'] = strtoupper($item['name']);
        $id = $item['old_id'];
        // var_dump($item);exit;
        unset($item['old_id']);
        $msg = 'to edit regency <b>'.$item['name'].'</b>';

        try{
            AB_Regency::where(DB::raw('md5(id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('edit regency',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete regency';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                AB_Regency::where(DB::raw('md5(id)'),'=',$id)->delete();
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
        
        AppLog::createLog('delete regency',$ids,$output);
        return json_encode($output);
    }
}
