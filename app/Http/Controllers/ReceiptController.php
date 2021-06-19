<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\Receipt_all;


class ReceiptController extends Controller
{

    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($receipt)
    {
        $company = CompanyProfile::find(1);
        $receipt = Receipt_all::find($receipt);


        return view('documents.printreceipt', compact('receipt', 'company'));
    }
}
