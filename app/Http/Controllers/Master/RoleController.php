<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Active;
use DB;

class RoleController extends Controller
{
    public function index(Request $request){
        // $this->data = array(); // data tdk boleh direplace karena ada bawaan dari construct
        $where = "1=1";
        if(!empty($request->all())){
            $active = $request->get('active');
            if($active != "" && $active != "all") $where .= " AND active = ".$active;
            $role_name = $request->get('role_name');
            if($role_name != "") $where .= " AND role_name LIKE '%".$role_name."%'";
        }

        $this->data['role'] = Role::whereRaw($where)->get();
        $this->data['active'] =  Active::getList();
        $this->data['footer'] = 'include.role';

        return view('pages.master.role.index',$this->data);
    }

    public function add(){
        $this->data['active'] =  Active::getList();
        return view('pages.master.role.add',$this->data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->all();
        $status = 'success';
        $msg = 'to add new role '.$item['role_name'];

        try{
            DB::table('ms_roles')->insertGetId($item);
        }catch(\Exception $e){
            $status = 'fail';
        }

        $request->session()->flash($status,$msg);
        
        return redirect('_admin/master/role');
    }

    public function detail($id){
        $item = Role::where('id','=',$id)->first();

        $data = array(
            "id"=>$item->id,
            "role_name"=>$item->role_name,
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
            'msg' => 'Success to edit new role '.$item['role_name']
        );

        try{
            DB::table('ms_roles')->where('id',$request['id'])->update($item);
        }catch(Exception $e){
            $data['status'] = 'fail';
            $data['msg'] = 'Fail to edit new role '.$item['role_name'];
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
            $deletedRows = Role::where('id','=',$id)->delete();
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
