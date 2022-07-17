<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Http\Models\RoleMenu;
use App\Http\Models\Menu;
use Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function __construct() {
        date_default_timezone_set("Asia/Jakarta");
        $this->data['authorize'] = array('create'=>0,'edit'=>0,'view'=>0,'delete'=>0,'execute'=>0);
        
        $this->middleware(function ($request, $next) { // supaya bisa ambil session di konstruktor
            if(session()->get('_user')){
                if( session()->get('_user')['_id'] && 
                    session()->get('_user')['_role'] && 
                    session()->get('_user')['_company']){
                    if(session()->get('_user')['_role'] == '7778'){
                        $this->data['authorize'] = array('create'=>1,'edit'=>1,'view'=>1,'delete'=>0,'execute'=>1);
                    }else{
                        $menus = Menu::where('active','=',1)->get(); // menu with auth
                        foreach ($menus as $menu) {
                            // echo "/".Request::path()." - ".$menu->slug." --> ".strpos(Request::path(),$menu->slug)."<br>";
                            if (strpos("/".Request::path(),$menu->slug) !== FALSE){
                                $this->data['authorize'] =  RoleMenu::where('menu_id','=',$menu->id)
                                                                ->where('role_id','=',session()->get('_user')['_role'])
                                                                ->first()->toArray();
                                // if flag view or (if an add page) flag create not 1 then unauthorized 
                                if(!$this->data['authorize']['view'] || (!$this->data['authorize']['create'] && strpos(Request::path(),$menu->slug.'/add') !== FALSE)){
                                    return redirect('_handle/unautorized');
                                }
                            }
                        }
                    }
                }
            }else{
                return redirect('/');
            }
            return $next($request);
        });
    }

}
