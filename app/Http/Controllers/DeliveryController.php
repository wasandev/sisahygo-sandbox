<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Delivery;
use App\Models\Delivery_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class DeliveryController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($delivery)
    {
        $report_title = 'ใบจัดส่งสินค้า';
        $company = CompanyProfile::find(1);
        $delivery = Delivery::find($delivery);
        $delivery_items = Delivery_item::with(['customer'])
            ->where('delivery_id', $delivery->id)->get();
        $item_groups = $delivery_items->groupBy('customer.district')->all();

        $delivery_district = $item_groups;

        return view('documents.printdelivery', compact('delivery', 'delivery_items', 'item_groups', 'delivery_district', 'company', 'report_title'));
    }


    // public function makePDF($delivery)
    // {
    //     $company = CompanyProfile::find(1);
    //     $delivery = Delivery::find($delivery);
    //     $delivery_items = Delivery_item::with(['customer'])
    //         ->where('delivery_id', $delivery->id)->get();
    //     $item_groups = $delivery_items->groupBy('customer.district')->all();

    //     $delivery_district = $item_groups;

    //     PDF::setOptions(['fontHeightRatio' => 1.0]);
    //     $pdf = PDF::loadView('documents.printdelivery', compact('delivery', 'delivery_items', 'item_groups', 'delivery_district', 'company'));
    //     $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $delivery->delivery_no  . '.pdf';
    //     $pdf->save($path);
    //     return $pdf->stream($path);
    // }
}
