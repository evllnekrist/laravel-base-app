<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\AB_Province;
use App\Http\Models\AB_Regency;
use App\Http\Models\AB_District;
use App\Http\Models\AB_Village;
use App\Http\Models\Active;
use DB;

class AB_VillageController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._data.ab_village');
    }
    
    public function index(Request $request){
        return view('_page._data.index-ab_village',$this->data);
    }
    
    public function get(Request $request){

        $columns = array(
            0 =>'village_id',
            1 =>'province_name',
            2 =>'regency_name',
            3 =>'district_name',
            4 =>'village_name',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // DB::enableQueryLog(); // Enable query log
        $models =  DB::table('ms_ab_villages as v')
                        ->select('v.village_id','v.name as village_name','d.name as district_name','r.name as regency_name', 'p.name as province_name')
                        ->leftJoin('ms_ab_districts as d', 'd.id', '=', 'v.district_id')
                        ->leftJoin('ms_ab_regencies as r', 'r.id', '=', 'd.regency_id')
                        ->leftJoin('ms_ab_provinces as p', 'p.id', '=', 'r.province_id');
                        // ->where('u.active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('r.name','LIKE',"%{$search}%")
                                ->orWhere('v.name','LIKE',"%{$search}%")
                                ->orWhere('d.name','LIKE',"%{$search}%")
                                ->orWhere('p.name','LIKE',"%{$search}%");
                    });
        }
        // dd(DB::getQueryLog()); // Show results of log
        $recordsFiltered = $models->orderBy($order,$dir)->get()->count();        
        $recordsTotal = AB_Village::count(); // where('active','=',1)

        $models = $models->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $data = array();
        if(!empty($models)) {
            
            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->village_id;
                $nestedData[] = $model->province_name; 
                $nestedData[] = $model->regency_name;
                $nestedData[] = $model->district_name;
                $nestedData[] = $model->village_name;
                $action = '';
                if($this->data['authorize']['edit']==1){
                    $action .=   "   <span class='action-edit' data-hash='".md5($model->village_id)."' data-title=''>
                                        <i class='feather icon-edit'></i>
                                    </span>";
                }
                if($this->data['authorize']['delete']==1){
                    $action .=   "   <span class='action-delete' data-hash='".md5($model->village_id)."' data-title=''>
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
        $msg = 'to add village <b>'.$item['name'].'</b>';
        
        try{
            AB_Village::insert($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('add village',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){
        $item = AB_Village::where(DB::raw('md5(village_id)'),'=',$id)->first();
        $single_district = AB_District::where('id','=',$item['district_id'])->first();
        $list_district = AB_District::where('regency_id','=',$single_district['regency_id'])->get();

        $single_regency = AB_Regency::where('id','=',$single_district['regency_id'])->first();
        $list_regency = AB_Regency::where('province_id','=',$single_regency['province_id'])->get();
        $list_province = AB_Province::get();

        $data = array(
            "detail"=>$item,
            "list_province"=>$list_province,
            "list_regency"=>$list_regency,
            "list_district"=>$list_district,
            "single_district"=>$single_district,
        );
        
        return response()->json($data);
    }

    public function detailRegency($id){
        $item = AB_Regency::where('province_id','=',$id)->get();

        $data = array(
            "detail"=>$item,
        );
        
        return response()->json($data);
    }

    public function detailDistrict($id){
        $item = AB_District::where('regency_id','=',$id)->get();

        $data = array(
            "detail"=>$item,
        );
        
        return response()->json($data);
    }

    public function detailVillage($id){
        $item = AB_Village::where('district_id','=',$id)->orderBy('village_id','DESC')->first();

        $data = array(
            "detail"=>$item,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['name'] = strtoupper($item['name']);
        // $item['updated_by'] = \Session::get('_user')['_id'];
        $id = $item['old_id'];
        // var_dump($item);exit;
        unset($item['old_id']);
        $msg = 'to edit village <b>'.$item['name'].'</b>';

        try{
            AB_Village::where(DB::raw('md5(village_id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('edit village',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete village';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                AB_Village::where(DB::raw('md5(village_id)'),'=',$id)->delete();
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
        
        AppLog::createLog('delete village',$ids,$output);
        return json_encode($output);
    }
}
