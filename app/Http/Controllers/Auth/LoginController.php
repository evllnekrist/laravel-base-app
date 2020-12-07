<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Models\User;
use App\Http\Models\Role;
use App\Http\Models\Menu;
use App\Http\Models\RoleMenu;
// use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        return view('_page._auth.login');
    }

	public function logout(Request $request)
	{
		Session::flush();// removes all session data
		return redirect()->route('login');
    }
    
    private function pwd_encrypt($string){
		return md5(md5($string));
    }

    private function get_initial($string){
        $words = preg_split("/[\s,_-]+/", $string);
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= $w[0];
        }
        return $acronym;
    }
    
    public function ajax_doLogin(Request $request){
        // dump($request->all());
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);
        
        $user = User::where('username','=',$request->get('username'))->where('password','=',$this->pwd_encrypt($request->get('password')))->first();
        // dd($user);

        if($user){ 
            $user_role = Role::where('id','=',$user['role_id'])->pluck('name');
            if(!empty($user_role)){
                Session::put('_user',array(
                    '_id' => $user['id'],
                    '_username' => $user['username'],
                    '_fullname' => $user['fullname'],
                    '_initial' => $this->get_initial($user['fullname']),
                    '_role' => $user['role_id'],
                    '_role_name' => $user_role[0],
                    '_company' => $user['company_id'],
                    '_menu' => $this->menuReady($user['role_id'])
                ));
                return json_encode(array('status'=>true));
            }else{
                return json_encode(array('status'=>false, 'message'=>'user exist but your role invalid'));
            }
        }else{
			return json_encode(array('status'=>false, 'message'=>'user with filled username & password does not exist'));
        }
        // dd(session()->all());
    }
    
    public function menuReady($role_id){
        // DB::enableQueryLog();
        $raw_role_menu = RoleMenu::join('ms_menu','menu_id','=','ms_menu.id')
                                ->where('role_id','=',$role_id)->get();
        // dd(DB::getQueryLog());
        $cooked_role_menu = array();
        foreach ($raw_role_menu as $key => $value) {
            if($value->view == 1){
                if($value->parent){
                    $isParent = Menu::where('id','=',$value->parent)->first();

                    if($isParent->parent){ //are subparent that have another parent //support 2 level only
                        $isParentParent = Menu::where('id','=',$isParent->parent)->first();
                        $cooked_role_menu[$isParent->parent]['icon'] = $isParentParent->icon;
                        $cooked_role_menu[$isParent->parent]['title'] = $isParentParent->name;
                        $cooked_role_menu[$isParent->parent]['detail'][$isParent->id]['icon'] = $isParent->icon;
                        $cooked_role_menu[$isParent->parent]['detail'][$isParent->id]['title'] = $isParent->name;
                        $cooked_role_menu[$isParent->parent]['detail'][$isParent->id]['detail'][$value->id] = $value;
                        // echo ' Parent-Parent';
                    }else{ //final parent
                        $cooked_role_menu[$isParent->id]['icon'] = $isParent->icon;
                        $cooked_role_menu[$isParent->id]['title'] = $isParent->name;
                        $cooked_role_menu[$isParent->id]['detail'][$value->id] = $value;
                        // echo ' Parent';
                    }
                    // echo 'DEPENDENT >> '.$value->id." - ".$value->name.'<br>';
                }else{
                    $cooked_role_menu[$value->id]['detail'] = $value;
                    // echo 'INDEPENDENT >> '.$value->id." - ".$value->name.'<br>';
                }
            }
        }
        // dd($cooked_role_menu); // parent sub-parentnya ga usah di assign, akan otomatis kalau childnya dicentang
        sort($cooked_role_menu);
        return $cooked_role_menu;
    }

}
