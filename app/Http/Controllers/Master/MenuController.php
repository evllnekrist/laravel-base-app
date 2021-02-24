<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\Menu;
use App\Http\Models\MenuType;
use App\Http\Models\Active;
use DB;

class MenuController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._app.menu');
    }
    
    public function index(Request $request){
        return view('_page._app.index-menu',$this->data);
    }

    public function get(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'name',
            2 =>'slug',
            3 =>'icon',
            6 =>'type',
            5 =>'parent', 
            6 =>'need_auth'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $models =  Menu::where('active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('name','LIKE',"%{$search}%")
                                ->orWhere('slug', 'LIKE',"%{$search}%")
                                ->orWhere('icon', 'LIKE',"%{$search}%");
                    });
        }
        $recordsFiltered = $models->orderBy($order,$dir)->get()->count(); 
        $recordsTotal = Menu::where('active','=',1)->count();

        $models = $models->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $data = array();
        if(!empty($models)) {

            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->name;
                $nestedData[] = $model->slug;
                $nestedData[] = $model->icon;
                $nestedData[] = $model->type;
                $nestedData[] = $model->parent;
                $nestedData[] = ($model->need_auth? '<i class="feather icon-check ft-blue-band"></i>':'');
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
        $list_menu_type = MenuType::getList();
        $list_parent = Menu::where('active','=',1)->whereIn('type',$list_menu_type)->get();

        $data = array(
            "list_menu_type"=>$list_menu_type,
            "list_parent"=>$list_parent,
        );
        
        return response()->json($data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->get('params');
        $item['created_by'] = \Session::get('_user')['_id'];
        $item['created_at'] = date('Y-m-d h:i:s');
        $msg = 'to add new menu <b>'.$item['name'].'</b>';
        
        try{
            Menu::insertGetId($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>json_encode($e));
        }

        AppLog::createLog('add menu',$item,$output);
        return json_encode($output);
    }

    public function detailEdit($id){ // the id in hash
        $item = Menu::where(DB::raw('md5(id)'),'=',$id)->first();
        $list_menu_type = MenuType::getList();
        $list_parent = Menu::where('active','=',1)->whereIn('type',$list_menu_type)->get();

        $data = array(
            "detail"=>$item,
            "list_menu_type"=>$list_menu_type,
            "list_parent"=>$list_parent,
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
        $msg = 'to edit menu <b>'.$item['name'].'</b>';
        
        try{
            Menu::where(DB::raw('md5(id)'),'=',$id)->update($item);
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>json_encode($e));
        }

        AppLog::createLog('edit menu',$item,$output);
        return json_encode($output);
    }

    public function delete($ids){ // the id in hash 
        $array_id = explode(",",$ids);
        $msg = 'to delete menu';
        $deletedRows = 0;
        
        try{
            foreach ($array_id as $id) {
                Menu::where(DB::raw('md5(id)'),'=',$id)->delete();
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
        
        AppLog::createLog('delete menu',$ids,$output);
        return json_encode($output);
    }
}
