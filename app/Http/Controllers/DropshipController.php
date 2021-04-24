<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Order_header;
use App\Models\Dropship_tran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class DropshipController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function preview($dropship_tran)
    {
        $company = CompanyProfile::find(1);
        $dropship_tran = Dropship_tran::find($dropship_tran);
        $order = Order_header::with(['to_branch'])
            ->where('dropship_tran_id', $dropship_tran->id)->get();

        $order_groups = $order->groupBy('to_branch.name')->all();

        $order_branch = $order_groups;

        return view('documents.printdropship_tran', compact('dropship_tran', 'order', 'order_groups', 'order_branch', 'company'));
    }


    public function makePDF($dropship_tran)
    {
        $company = CompanyProfile::find(1);
        $dropship_tran = Dropship_tran::find($dropship_tran);
        $order = Order_header::with(['to_branch'])
            ->where('dropship_tran_id', $dropship_tran->id)->get();

        $order_groups = $order->groupBy('to_branch.name')->all();

        $order_branch = $order_groups;
        PDF::setOptions(['fontHeightRatio' => 1.0]);
        $pdf = PDF::loadView('documents.printdropship_tran', compact('dropship_tran', 'order', 'order_groups', 'order_branch', 'company'));
        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $dropship_tran->dropship_tran_no  . '.pdf';
        $pdf->save($path);
        return $pdf->stream($path);
    }

    // public function waybillBydate($from_date, $to_date)
    // {
    //     $company = CompanyProfile::find(1);
    //     $waybills = Waybill::with(['routeto_branch'])
    //         ->where('waybill_date', '>=', $from_date)
    //         ->where('waybill_date', '<=', $to_date)
    //         ->whereNotIn('waybill_status', ['loading', 'cencle'])
    //         ->get();



    //     $waybill_groups = $waybills->groupBy('routeto_branch.name')->all();



    //     $waybill_branch = $waybill_groups;
    //     PDF::setOptions(['fontHeightRatio' => 0.8]);
    //     $pdf = PDF::loadView('reports.waybilldate', compact('from_date', 'to_date', 'waybills', 'waybill_groups', 'waybill_branch', 'company'))
    //         ->setPaper('a4', 'landscape');
    //     $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'reports/' . 'waybill' . $from_date . '.pdf';
    //     $pdf->save($path);
    //     return $pdf->stream($path);
    // }
    // public function waybillBydatePreview()
    // {
    //     $from_date = '2021-01-01';
    //     $to_date = '2021-01-13';
    //     $company = CompanyProfile::find(1);
    //     $waybills = Waybill::with(['routeto_branch'])
    //         ->where('waybill_date', '>=', $from_date)
    //         ->where('waybill_date', '<=', $to_date)
    //         ->whereNotIn('waybill_status', ['loading', 'cancle'])
    //         ->get();



    //     $waybill_groups = $waybills->groupBy('routeto_branch.name')->all();



    //     $waybill_branch = $waybill_groups;
    //     return view('reports.waybilldate', compact('from_date', 'to_date', 'waybills', 'waybill_groups', 'waybill_branch', 'company'));
    // }
}
