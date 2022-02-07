<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Order_header;
use App\Models\Waybill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $pdf = PDF::loadView('documents.printwaybillpdf', compact('waybill', 'order', 'order_groups', 'order_district', 'company'));
        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $waybill->waybill_no  . '.pdf';
        $pdf->save($path);
        return $pdf->stream($path);
    }

    public function report_10($routetobranch, $from, $to)
    {
        $report_title = 'รายงานรถออกประจำวัน';
        $company = CompanyProfile::find(1);
        if ($routetobranch == 'all') {
            foreach (Waybill::lazy() as $waybill) {
                $waybills = $waybill::whereDate('departure_at', '>=', $from)
                    ->whereDate('departure_at', '<=', $to)
                    ->whereNotIn('waybill_status', ['loading', 'cancel'])
                    //    ->lazyById(200, $column = 'id');
                    ->orderBy('waybill_date', 'asc')
                    ->orderBy('branch_rec_id', 'asc')
                    ->orderBy('waybill_type', 'asc')
                    ->get();
            }
        } else {
            foreach (Waybill::lazy() as $waybill) {
                $waybills = $waybill::whereDate('departure_at', '>=', $from)
                    ->whereDate('departure_at', '<=', $to)
                    ->where('routeto_branch_id', '=', $routetobranch)
                    ->whereNotIn('waybill_status', ['loading', 'cancel'])
                    // ->lazyById(200, $column = 'id');
                    ->orderBy('id', 'asc')
                    ->orderBy('waybill_date', 'asc')
                    ->orderBy('branch_rec_id', 'asc')
                    ->orderBy('waybill_type', 'asc')
                    ->get();
            }
        }

        $waybill_groups = $waybills->all();

        $waybill_groups = $waybills->groupBy([
            function ($item) {
                return $item->departure_at->format('Y-m-d');
            },
            'branch_rec_id', 'waybill_type'
        ]);



        $waybill_groups = $waybill_groups->all();

        return view('reports.waybilldate', compact('company', 'report_title', 'waybills', 'waybill_groups', 'routetobranch', 'from', 'to'));
    }
}
