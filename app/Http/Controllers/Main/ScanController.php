<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use Illuminate\Support\Facades\View;
use DB;

class ScanController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['header_data']['js'] = array('.scan');
    }

    public function index(Request $request){
        return view('_page._main.index-scan',$this->data);
    }

}
