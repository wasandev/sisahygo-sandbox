<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\Invoice;


class InvoiceController extends Controller
{

    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($invoice)
    {
        $company = CompanyProfile::find(1);
        $invoice = Invoice::find($invoice);


        return view('documents.printinvoice', compact('invoice', 'company'));
    }
}
