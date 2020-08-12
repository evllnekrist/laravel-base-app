<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\RoleMenu;
use App\Models\Menu;
use Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function __construct() {
        $this->data['authorize'] = array('create' => 0, 'edit' => 0, 'view' => 0, 'delete' => 0);
        
        $this->middleware(function ($request, $next) { // supaya bisa ambil session di konstruktor
            if(session()->get('user')['role_id']){
                $menus = Menu::where('active','=',1)->get(); // menu with auth
                foreach ($menus as $menu) {
                    if (strpos(Request::path(),$menu->slug) !== FALSE){
                        $this->data['authorize'] =  RoleMenu::where('menu_id','=',$menu->id)
                                                        ->where('role_id','=',session()->get('user')['role_id'])
                                                        ->first()->toArray();
                        // if flag view or (if an add page) flag create not 1 then unauthorized 
                        if(!$this->data['authorize']['view'] || (!$this->data['authorize']['create'] && strpos(Request::path(),$menu->slug.'/add') !== FALSE)){
                            return redirect('_handle/unautorized');
                        }
                    }
                }
            }
            return $next($request);
        });
    }

}
