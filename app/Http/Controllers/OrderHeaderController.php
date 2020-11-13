<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order_header;
use App\Models\CompanyProfile;
use PDF;
use Illuminate\Support\Facades\Storage;

class OrderHeaderController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($order)
    {

        $company = CompanyProfile::find(1);
        $order = Order_header::find($order);

        return view('documents.printorder', compact('order', 'company'));
    }



    public function makePDF($order)
    {
        $company = CompanyProfile::find(1);
        $order = Order_header::find($order);

        $pdf = PDF::loadView('documents.printorder', compact('order', 'company'));

        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $order->order_header_no . '.pdf';
        $pdf->save($path);

        return $path;
    }
}
