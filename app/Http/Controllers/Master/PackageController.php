<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\Site;
use App\Http\Models\Package;
use App\Http\Models\Active;
use DB;

class PackageController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._data.package');
    }
    
    public function index(Request $request){
        return view('_page._data.index-package',$this->data);
    }
    
    public function get(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'code',
            2 =>'name',
            3 =>'active',
            4 =>'site_name',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        // DB::enableQueryLog(); // Enable query log
        $models =  DB::table('ms_package as p')
                        ->select('p.id', 'p.code','p.name', 'p.site_code', 'p.active', 's.name as site_name')
                        ->leftJoin('ms_site as s', 'p.site_code', '=', 's.code');
                        // ->where('u.active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('name','LIKE',"%{$search}%")
                                ->orWhere('site_code','LIKE',"%{$search}%")
                                ->orWhere('site_name', 'LIKE',"%{$search}%");
                    });
        }
        $models = $models->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        // dd(DB::getQueryLog()); // Show results of log

        $recordsFiltered = count(get_object_vars($models));        
        $recordsTotal = Package::count(); // where('active','=',1)

        $data = array();
        if(!empty($models)) {
            
            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->code;
                $nestedData[] = $model->name;
                $nestedData[] = ($model->active? '<i class="feather icon-check ft-blue-band"></i>':'');
                $nestedData[] = $model->site_name; 
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
        $list_site = Site::where('active','=',1)->get();

        $data = array(
            "list_site"=>$list_site,
        );
        
        return response()->json($data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['created_by'] = \Session::get('_user')['_id'];
        $msg = 'to add package <b>'.$item['name'].'</b>';
        
        try{
            Package::insert($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('add package',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){
        $item = Package::where(DB::raw('md5(id)'),'=',$id)->first();
        $list_site = Site::where('active','=',1)->get();

        $data = array(
            "detail"=>$item,
            "list_site"=>$list_site,
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
        $msg = 'to edit package <b>'.$item['name'].'</b>';

        try{
            Package::where(DB::raw('md5(id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('edit package',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete package';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                Package::where(DB::raw('md5(id)'),'=',$id)->delete();
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
        
        AppLog::createLog('delete package',$ids,$output);
        return json_encode($output);
    }
}
