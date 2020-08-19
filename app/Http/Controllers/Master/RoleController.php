<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Role;
use App\Http\Models\Active;
use DB;

class RoleController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._app.role');
    }
    
    public function index(Request $request){
        return view('_page._app.index-role',$this->data);
    }

    public function get(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'name',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $models =  Role::where('active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('name','LIKE',"%{$search}%");
                    });
        }
        $models = $models->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

        $recordsFiltered = count(get_object_vars($models));        
        $recordsTotal = Role::where('active','=',1)->count();

        $data = array();
        if(!empty($models)) {

            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->name;
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

    public function detailAdd(){ // currently not used
        // $data = array();
        // return response()->json($data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $msg = 'to add new role <b>'.$item['name'].'</b>';

        try{
            Role::insertGetId($item);
            return json_encode(array('status'=>true, 'message'=>'Success '.$msg));
        }catch(\Exception $e){
            return json_encode(array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->errorInfo[2]));
        }
    }

    public function detailEdit($id){ // the id in hash
        $item = Role::where(DB::raw('md5(id)'),'=',$id)->first();

        $data = array(
            "detail"=>$item,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $id = $item['id'];
        unset($item['id']);
        $msg = 'to edit role <b>'.$item['name'].'</b>';

        try{
            Role::where(DB::raw('md5(id)'),'=',$id)->update($item);
            return json_encode(array('status'=>true, 'message'=>'Success '.$msg));
        }catch(Exception $e){
            return json_encode(array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->errorInfo[2]));
        }
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete role';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                Role::where(DB::raw('md5(id)'),'=',$id)->delete();
                $deletedRows++;
            }
        }catch(Exception $e){
            return json_encode(array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData()));
        }
        
        if($deletedRows >= 1){
            $s = ($deletedRows > 1) ? "'s" : "";
            return json_encode(array('status'=>true, 'message'=>'Success '.$msg.$s.' ['.$deletedRows.' row'.$s.']'));
        }else{
            return json_encode(array('status'=>false, 'message'=>'Selected data unavailable in database', 'detail'=>''));
        }
    }
}
