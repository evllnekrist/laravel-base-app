<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\Active;
use DB;

class MenuController extends Controller
{
    public function index(Request $request){
        // $this->data = array(); // data tdk boleh direplace karena ada bawaan dari construct
        $where = "1=1";
        if(!empty($request->all())){
            $active = $request->get('active');
            if($active != "" && $active != "all") $where .= " AND active = ".$active;
            $name = $request->get('name');
            if($name != "") $where .= " AND (name LIKE '%".$name."%' OR slug LIKE '%".$name."%' OR icon LIKE '%".$name."%')";
        }

        $this->data['menu'] = Menu::whereRaw($where)->get();
        $this->data['menu_type'] = MenuType::getList();
        $this->data['menu_parent'] = Menu::whereRaw("type IN ('". implode("','",$this->data['menu_type']) ."')")->get();
        $this->data['active'] =  Active::getList();
        $this->data['footer'] = 'include.menu';

        return view('pages.master.menu.index',$this->data);
    }

    public function add(){
        $this->data['menu_type'] = MenuType::getList();
        $this->data['menu_parent'] = Menu::whereRaw("type IN ('". implode("','",$this->data['menu_type']) ."')")->get();
        $this->data['active'] =  Active::getList();
        return view('pages.master.menu.add',$this->data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->all();
        $status = 'success';
        $msg = 'to add new menu '.$item['name'];
        
        try{
            DB::table('ms_menus')->insertGetId($item);
        }catch(\Exception $e){
            $status = 'fail';
        }
        
        $request->session()->flash($status,$msg);
        
        return redirect('_admin/master/menu');
    }

    public function detail($id){
        $item = Menu::where('id','=',$id)->first();

        $data = array(
            "id"=>$item->id,
            "name"=>$item->name,
            "slug"=>$item->slug,
            "icon"=>$item->icon,
            "parent"=>$item->parent,
            "type"=>$item->type,
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
            'msg' => 'Success to edit new menu '.$item['name']
        );

        try{
            DB::table('ms_menus')->where('id',$request['id'])->update($item);
        }catch(Exception $e){
            $data['status'] = 'fail';
            $data['msg'] = 'Fail to edit new menu '.$item['name'];
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
            $deletedRows = Menu::where('id','=',$id)->delete();
        }catch(Exception $e){
            $msg = $e->getData();
        }
        
        if($deletedRows == 1){
            $data['status'] = 'success';
            $data['msg'] = 'Success to delete menu';
        }

        return response()->json($data);
    }
}
