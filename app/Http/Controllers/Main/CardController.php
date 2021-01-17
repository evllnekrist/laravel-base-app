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
        $pdf->SetFont('Arial','B',12);
        // $pdf->SetXY(0,5); 
        $pdf->setfillcolor(175,175,175);
        $pdf->Rect(0, 8, 90, 15, 'F');
        // $pdf->Rect(0, 8, 90, 1, 'F');
        // $pdf->Rect(0, 9, 5, 14, 'F');
        // $pdf->Rect(85, 9, 5, 14, 'F');
        // $pdf->Rect(0, 19, 90, 1, 'F');
        $pdf->setfillcolor(0,0,0);
        $pdf->Code128(5,9,$item['card_id'],80,10);
        $pdf->Image(asset('app-assets/images/card/Membership-01.png'),0, 20, 90, 35,'PNG');
        // $pdf->Cell(5,0,'hgfhjsdfg jhgfdjhsfgd hgdfhjas mjgdfjskadfg jhdsfgjhasd mhsdfgjhasf hjsdafjha jhdsfj');
        // $pdf->SetXY(0,10);
        // $pdf->Cell(5,0,$item['id']);
        $pdf->Output();
        exit;
    }
}
