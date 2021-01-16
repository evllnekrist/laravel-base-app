<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\RoleMenu;
use App\Http\Models\Role;
use App\Http\Models\Menu;
use App\Http\Models\Active;
use DB;

class RoleMenuController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('._app.role-menu');
    }
    
    public function index(Request $request){
        return view('_page._app.index-role-menu',$this->data);
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

        // DB::enableQueryLog(); // Enable query log
        $models =  Role::where('active','=',1);
        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $models = $models->where(function($query) use ($search){
                        $query->where('name','LIKE',"%{$search}%");
                    });
        }
        // dd(DB::getQueryLog()); // Show results of log
        $recordsFiltered = $models->orderBy($order,$dir)->get()->count();        
        $recordsTotal = Role::where('active','=',1)->count();

        $models = $models->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        $data = array();
        if(!empty($models)) {
            
            if($this->data['authorize']['edit']==1){
                $icon_edit = 'feather icon-edit';
            }else{
                $icon_edit = 'feather icon-eye';
            }

            foreach ($models as $model) {
                $nestedData=array();
                $nestedData[] = null;
                $nestedData[] = $model->name;
                $action =   "   <span class='action-edit' data-hash='".md5($model->id)."' data-title=''>
                                    <i class='".$icon_edit."'></i>
                                </span>";
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

    public function detailEdit($role_id){
        // DB::enableQueryLog();
        $item           =   Role::where(DB::raw('md5(id)'),'=',$role_id)->first();
        if (RoleMenu::where(DB::raw('md5(role_id)'),'=',$role_id)->exists()) {
            $list_menu  =   Menu::select('ms_menu.id','ms_menu.name','create','edit','view','delete','execute')
                                ->leftJoin('ms_role_menu', function($join) use ($role_id) {
                                    $join->on('ms_role_menu.menu_id','=','ms_menu.id');
                                    $join->on(DB::raw('md5(ms_role_menu.role_id)'),'=',DB::raw('"'.$role_id.'"')); // db raw to prevent quotes
                                })
                                ->where('type','=',null) // neither parent or sub-parent
                                ->where('ms_menu.active','=','1')->get();
        }else{
            $list_menu  =   Menu::where('type','=',null) // neither parent or sub-parent
                                ->where('ms_menu.active','=','1')->get();
        }
        // $list_active    =   Active::getList();
        // dd(DB::getQueryLog());

        $data = array(
            "detail"=>$item,
            "list_menu"=>$list_menu,
            // "list_active"=>$list_active,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        $item = $request->get('params');
        $msg = 'to map role <b>'.$item['name'].' with menus</b>';
        // dump($request->get('params'));die;

        try{
            foreach($item['detail']  as $key => $value){
                $status_detail[$key] = RoleMenu::updateOrCreate(
                    [
                        'role_id' => $item['id'], 
                        'menu_id' => $value['id']
                    ],
                    [   
                        'view' => $value['view'],
                        'create' => $value['create'],
                        'edit' => $value['edit'],
                        'delete' => $value['delete'],
                        'execute' => $value['execute'],
                        'updated_by' => \Session::get('_user')['_id']
                    ]
                );
            }
            $output = array('status'=>true, 'message'=>'Success '.$msg);
        }catch(\Exception $e){
            $output = array('status'=>false, 'message'=>'Failed '.$msg, 'detail'=>$e->getData());
        }

        AppLog::createLog('mapping role-menu',$item,$output);
        return json_encode($output);
    }
}
