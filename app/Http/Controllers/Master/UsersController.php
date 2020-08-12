<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\Active;
use DB;

class UsersController extends Controller
{
    public function index(Request $request){
        // $this->data = array(); // data tdk boleh direplace karena ada bawaan dari construct
        $where = "1=1";
        if(!empty($request->all())){
            $active = $request->get('active');
            if($active != "" && $active != "all") $where .= " AND ms_users.active = ".$active;
            $username = $request->get('username');
            if($username != "") $where .= " AND username LIKE '%".$username."%'";
            $role_id = $request->get('role_id');
            if($role_id != "" && $role_id != "all") $where .= " AND role_id LIKE '%".$role_id."%'";
            $company_id = $request->get('company_id');
            if($company_id != "" && $role_id != "all") $where .= " AND company_id LIKE '%".$company_id."%'";
        }

        $this->data['user'] = User::select('ms_users.id','username','role_id','role_name','company_id','company_name','ms_users.active')
                            ->leftJoin('ms_roles','ms_roles.id','=','role_id')
                            ->leftJoin('ms_company','ms_company.id','=','company_id')
                            ->whereRaw($where)->get();
        $this->data['role'] = Role::where('active','=','1')->get();
        $this->data['company'] = Company::get();
        $this->data['active'] =  Active::getList();
        $this->data['footer'] = 'include.users';

        return view('pages.master.users.index',$this->data);
    }

    public function add(){
        $this->data['role'] = Role::where('active','=','1')->get();
        $this->data['company'] = Company::get();
        $this->data['active'] =  Active::getList();
        return view('pages.master.users.add',$this->data);
    }

    public function doAdd(Request $request){
        unset($request['_token']);
        $item = $request->all();
        $item['password'] = Hash::make($item['password']);
        $status = 'success';
        $msg = 'to add new user';

        try{
            DB::table('ms_users')->insertGetId($item);
        }catch(\Exception $e){
            $status = 'fail';
        }

        $request->session()->flash($status,$msg);
        
        return redirect('_admin/master/users');
    }

    public function detail($id){
        $item = User::where('id','=',$id)->first();

        $data = array(
            "id"=>$item->id,
            "username"=>$item->username,
            "role_id"=>$item->role_id,
            "company_id"=>$item->company_id,
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
            'msg' => 'Success to edit user'
        );

        try{
            DB::table('ms_users')->where('id',$request['id'])->update($item);
        }catch(Exception $e){
            $data['status'] = 'fail';
            $data['msg'] = 'Fail to edit user';
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
            $deletedRows = User::where('id','=',$id)->delete();
        }catch(Exception $e){
            $msg = $e->getData();
        }
        
        if($deletedRows == 1){
            $data['status'] = 'success';
            $data['msg'] = 'Success to delete user';
        }

        return response()->json($data);
    }
}
