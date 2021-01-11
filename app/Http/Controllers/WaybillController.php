<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Order_header;
use App\Models\Waybill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class WaybillController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($waybill)
    {
        $company = CompanyProfile::find(1);
        $waybill = Waybill::find($waybill);
        $order = Order_header::with(['to_customer'])
            ->where('waybill_id', $waybill->id)->get();

        $order_groups = $order->groupBy('to_customer.district')->all();

        $order_district = $order_groups;

        return view('documents.printwaybill', compact('waybill', 'order', 'order_groups', 'order_district', 'company'));
    }


    public function makePDF($waybill)
    {
        $company = CompanyProfile::find(1);
        $waybill = Waybill::find($waybill);
        $order = Order_header::with(['to_customer'])
            ->where('waybill_id', $waybill->id)->get();

        $order_groups = $order->groupBy('to_customer.district')->all();

        $order_district = $order_groups;
        PDF::setOptions(['fontHeightRatio' => 1.0]);
        $pdf = PDF::loadView('documents.printwaybill', compact('waybill', 'order', 'order_groups', 'order_district', 'company'));
        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $waybill->waybill_no  . '.pdf';
        $pdf->save($path);
        return $pdf->stream($path);
    }
}
