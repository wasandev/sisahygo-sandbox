<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Charter_job;
use App\Models\CompanyProfile;
use PDF;
use Illuminate\Support\Facades\Storage;

class CharterJobController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($charterjob)
    {
        $company = CompanyProfile::find(1);
        $charterjob = Charter_job::find($charterjob);
        return view('documents.printcharterjob', compact('charterjob', 'company'));
    }



    public function makePDF($charterjob)
    {
        $company = CompanyProfile::find(1);
        $charterjob = Charter_job::find($charterjob);
        $pdf = PDF::loadView('documents.printcharterjob', compact('charterjob', 'company'));
        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $charterjob->job_no . '.pdf';
        $pdf->save($path);

        return $path;
    }
}
