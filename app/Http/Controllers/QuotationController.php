<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\CompanyProfile;
use PDF;
use Illuminate\Support\Facades\Storage;

class QuotationController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($quotation)
    {

        $company = CompanyProfile::find(1);
        $quotation = Quotation::find($quotation);
        return view('documents.printquotation', compact('quotation', 'company'));
    }



    public function makePDF($quotation)
    {
        $company = CompanyProfile::find(1);
        $quotation = Quotation::find($quotation);


        $pdf = PDF::loadView('documents.printquotation', compact('quotation', 'company'));

        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $quotation->quotation_no . '.pdf';
        $pdf->save($path);
        //return $pdf->stream($path);
        //return $pdf->download($path);
        return  $pdf->stream($quotation->quotation_no . '.pdf');
    }
}
