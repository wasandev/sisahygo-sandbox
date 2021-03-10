<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\Order_header;
use App\Models\Order_detail;
use PDF;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
        $order_detail = Order_detail::find($order);
        //$qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($order->tracking_no));

        switch ($company->orderprint_option) {
            case 'form1':
                return view('documents.printorder', compact('order', 'order_detail'));
                break;
            case 'form2':
                return view('documents.printorder_receipt', compact('order', 'order_detail', 'company'));
                break;
            case 'form3':
                return view('documents.printorder_thermal', compact('order', 'order_detail', 'company'));
            default:
                return view('documents.printorder_receipt', compact('order', 'order_detail', 'company'));
        }
    }


    public function makePDF($order)
    {
        $company = CompanyProfile::find(1);

        $order = Order_header::find($order);
        $order_detail = Order_detail::find($order);
        // $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($order->tracking_no));

        switch ($company->orderprint_option) {
            case 'form1':
                $pdf = PDF::loadView('documents.printorder', compact('order', 'order_detail', 'company'))
                    ->setPaper('a5', 'landscape');
                break;
            case 'form2':
                $pdf = PDF::loadView('documents.printorder_receipt', compact('order', 'order_detail', 'company'))
                    ->setPaper('a5', 'landscape');
                break;
            case 'form3':
                $pdf = PDF::loadView('documents.printorder', compact('order', 'order_detail', 'company'));
                break;
            default:
                $pdf = PDF::loadView('documents.printorder', compact('order', 'order_detail', 'company'))
                    ->setPaper('a5', 'landscape');
        }

        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $order->order_header_no  . '.pdf';
        $pdf->save($path);
        return $pdf->stream($path);

        // return  $pdf->stream($order->order_header_no . '.pdf');
    }
}
