<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Models\AppLog;
use App\Http\Models\Member;
use App\Http\Models\MemberRole;
use App\Http\Models\MemberStatus;
use App\Http\Models\Gender;
use App\Http\Models\Package;
use App\Http\Models\Active;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Main\PDF_Code128;
use DB;

class CardController extends Controller
{
	public function __construct()
	{
		parent::__construct();
    }
    
    public function pdf($id){
        $item = Member::where('id','=',$id)->with('status')->with('gender')->with('role')->first();
        $list_role = MemberRole::where('active','=',1)->get();
        $list_status = MemberStatus::where('active','=',1)->get();
        $list_gender = Gender::where('active','=',1)->get();

        // $pdf = new FPDF();
        $pdf = new PDF_Code128('L','mm',array(90,55));
        $pdf->AddPage();
        $pdf->AddFont('Montserrat_Bold','','Montserrat_Bold.php');
        $pdf->SetFont('Montserrat_Bold','',10);
        $pdf->SetXY(3,34.9);
        // $pdf->SetXY(3,34);
        $pdf->SetTextColor(176, 32, 41);
        // $pdf->Cell(5,0,$item['first_name']." ".$item['last_name']);
        $pdf->MultiCell(0, 0.01, $item['first_name']." ".$item['last_name'], 0, 'L');
        $pdf->setfillcolor(175,175,175);
        $pdf->Rect(0, 9, 90, 12, 'F');
        $pdf->setfillcolor(0,0,0);
        $pdf->Code128(2.8,10,$item['card_id'],85,10);
        $pdf->Image(asset('app-assets/images/card/card1.png'),2.5, 40, 85, 10,'PNG');
        // $pdf->Image(asset('app-assets/images/card/Membership-01.png'),0, 20, 90, 35,'PNG');
        $pdf->Output();
        exit;
    }
}
