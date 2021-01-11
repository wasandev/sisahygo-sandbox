<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order_header;
use App\Models\Order_detail;
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


        $order = Order_header::find($order);
        $order_detail = Order_detail::find($order);

        return view('documents.printorder', compact('order', 'order_detail'));
    }


    public function makePDF($order)
    {

        $order = Order_header::find($order);
        $order_detail = Order_detail::find($order);
        $pdf = PDF::loadView('documents.printorder', compact('order', 'order_detail'))
            ->setPaper('a5', 'landscape');
        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $order->order_header_no  . '.pdf';
        $pdf->save($path);
        return $pdf->stream($path);

        // return  $pdf->stream($order->order_header_no . '.pdf');
    }
}
