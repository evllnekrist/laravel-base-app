<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        // $where = "1=1";
        // if(!empty($request->all())){
        //     $role_id = $request->get('role_id');
        //     if($role_id != "" && $role_id != "all") $where .= " AND role_id LIKE '%".$role_id."%'";
        //     $menu_id = $request->get('menu_id');
        //     if($menu_id != "" && $role_id != "all") $where .= " AND menu_id LIKE '%".$menu_id."%'";
        // }

        // $this->data['role_menu'] = RoleMenu::select('ms_role_menu.id','role_id','role_name','menu_id','ms_menus.name as menu_name','create','edit','view','delete')
        //                             ->leftJoin('ms_roles','ms_roles.id','=','role_id')
        //                             ->leftJoin('ms_menus','ms_menus.id','=','menu_id')
        //                             ->whereRaw($where)->get();
        // $this->data['role'] = Role::where('active','=','1')->get();
        // $this->data['menu'] = Menu::where('active','=','1')->get();
        // $this->data['active'] =  Active::getList();
        // $this->data['footer'] = 'include.role-menu';

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
        $models = $models->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        // dd(DB::getQueryLog()); // Show results of log

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

    public function mapping($role_id){
        // DB::enableQueryLog();
        $this->data['role'] = Role::where('id','=',$role_id)->get();
        if (RoleMenu::where('role_id','=',$role_id)->exists()) {
            $this->data['menu'] = Menu::select('ms_menus.id','ms_menus.name','create','edit','view','delete')
                                ->leftJoin('ms_role_menu', function($join) use ($role_id) {
                                    $join->on('ms_role_menu.menu_id','=','ms_menus.id');
                                    $join->on('ms_role_menu.role_id','=',DB::raw($role_id)); // db raw to prevent quotes
                                })
                                ->where('type','=',null) // neither parent or sub-parent
                                ->where('ms_menus.active','=','1')->get();
        }else{
            $this->data['menu'] = Menu::where('type','=',null) // neither parent or sub-parent
                                ->where('ms_menus.active','=','1')->get();
        }
        // dd(DB::getQueryLog());
        $this->data['active'] =  Active::getList();
        return view('pages.master.role-menu.add',$this->data);
    }

    public function doMap(Request $request){
        unset($request['_token']);
        $item = $request->all();
        $status = 'success';
        $msg = 'to mapping role menu';
        $status_detail = array();
        try{
            foreach($item['menu_id']  as $key => $value){
                $status_detail[$key] = RoleMenu::updateOrCreate(
                    [
                        'role_id' => $item['role_id'], 
                        'menu_id' => $value],
                    [   
                        'create' => (array_key_exists('create', $item) && array_key_exists($key, $item['create']) && $item['create'][$key] == 'on' ? 1 : 0),
                        'edit' => (array_key_exists('edit', $item) && array_key_exists($key, $item['edit']) && $item['edit'][$key] == 'on' ? 1 : 0),
                        'view' => (array_key_exists('view', $item) && array_key_exists($key, $item['view']) && $item['view'][$key] == 'on' ? 1 : 0),
                        'delete' => (array_key_exists('delete', $item) && array_key_exists($key, $item['delete']) && $item['delete'][$key] == 'on' ? 1 : 0)]
                );
            }
        }catch(\Exception $e){
            $status = 'fail';
        }
        $request->session()->flash($status,$msg);
        
        return redirect('_admin/master/role-menu');
    }

    public function detail($id){
        $item = RoleMenu::where('id','=',$id)->first();

        $data = array(
            "id"=>$item->id,
            "role_id"=>$item->role_id,
            "menu_id"=>$item->menu_id,
            "create"=>$item->create,
            "edit"=>$item->edit,
            "view"=>$item->view,
            "delete"=>$item->delete,
            "active"=>$item->active,
        );
        
        return response()->json($data);
    }

    public function doEdit(Request $request){
        unset($request['_token']);
        $item = $request->all();
        unset($item['id']);
        $data = array(
            'status' => 'success',
            'msg' => 'Success to edit role menu'
        );

        try{
            DB::table('ms_role_menu')->where('id',$request['id'])->update($item);
        }catch(Exception $e){
            $data['status'] = 'fail';
            $data['msg'] = 'Fail to edit role menu';
        }
        
        return response()->json($data);
    }

    public function delete(Request $request){
        $id = $request['id'];
        $data = array();
        $data = array(
            'status' => 'fail',
            'msg' => "Internal Server Error"
        );
        $deleteRows = 0;
        try{
            $deletedRows = RoleMenu::where('id','=',$id)->delete();
        }catch(Exception $e){
            $msg = $e->getData();
        }
        
        if($deletedRows == 1){
            $data['status'] = 'success';
            $data['msg'] = 'Success to delete role';
        }

        return response()->json($data);
    }
}
