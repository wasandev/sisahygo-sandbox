<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Carpayment;
use App\Models\Quotation;
use App\Models\CompanyProfile;
use PDF;
use Illuminate\Support\Facades\Storage;

class CarpaymentController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($carpayment)
    {

        $company = CompanyProfile::find(1);
        $carpayment = Carpayment::find($carpayment);
        return view('documents.printcarpayment', compact('carpayment', 'company'));
    }



    public function makePDF($carpayment)
    {
        $company = CompanyProfile::find(1);
        $carpayment = Quotation::find($carpayment);


        $pdf = PDF::loadView('documents.printcarpayment', compact('carpayment', 'company'));

        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $carpayment->carpayment_no . '.pdf';
        $pdf->save($path);
        //return $pdf->stream($path);
        //return $pdf->download($path);
        return  $pdf->stream($carpayment->carpayment_no . '.pdf');
    }
}
