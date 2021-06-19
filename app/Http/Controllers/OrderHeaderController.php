<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Order_header;
use App\Models\Order_detail;
use Illuminate\Support\Facades\DB;
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
                    ->setPaper('a4');
                break;
            case 'form2':
                $pdf = PDF::loadView('documents.printorderpdf_receipt', compact('order', 'order_detail', 'company'))
                    ->setPaper('a4');
                break;
            case 'form3':
                $pdf = PDF::loadView('documents.printorder', compact('order', 'order_detail', 'company'));
                break;
            default:
                $pdf = PDF::loadView('documents.printorderpdf_receipt', compact('order', 'order_detail', 'company'))
                    ->setPaper('a4');
        }

        $path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $order->order_header_no  . '.pdf';
        $pdf->save($path);
        return $pdf->stream($path);

        // return  $pdf->stream($order->order_header_no . '.pdf');
    }

    public function report_1($branch, $orderdate)
    {
        $report_title = 'รายงานนำส่งเงินสดประจำวัน';
        $company = CompanyProfile::find(1);
        $branchdata = Branch::find($branch);
        $order = Order_header::whereNotIn('order_headers.order_status', ['checking', 'new'])
            ->where('paymenttype', 'H')
            ->where('branch_id', $branch)
            ->where('order_header_date', '=', $orderdate)
            ->orderBy('branch_id', 'asc')
            ->orderBy('order_header_date', 'asc')
            ->get();
        $order_groups = $order->groupBy('user.name')->all();

        $order_user = $order_groups;
        return view('reports.orderbillingcash', compact('company', 'report_title', 'order', 'order_user', 'branchdata', 'orderdate'));
    }

    public function report_2($branch, $from, $to, $cancelflag)
    {
        $report_title = 'รายงานรายการขนส่งประจำวัน';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);


        if ($cancelflag == 'true') {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        } else {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking'])
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        }


        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportbillbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to', 'cancelflag'));
    }
    public function report_3($branch, $from, $to)
    {
        $report_title = 'รายงานยอดค่าขนส่งตามวัน';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);


        $order = Order_header::where('branch_id', $branch)
            ->where('order_header_date', '>=', $from)
            ->where('order_header_date', '<=', $to)
            ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
            ->orderBy('order_header_date', 'asc')
            ->orderBy('order_type', 'asc')
            ->get();


        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }


    public function report_4($branch, $from, $to)
    {
        $report_title = 'รายงานยอดค่าขนส่งตามสาขาปลายทาง';
        $company = CompanyProfile::find(1);

        if ($branch != 'all') {
            $branchdata = Branch::find($branch);

            $order = Order_header::where('branch_rec_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->orderBy('order_header_date', 'asc')
                ->orderBy('branch_rec_id', 'asc')
                ->get();
        } else {
            $branchdata = null;
            $order = Order_header::where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->orderBy('order_header_date', 'asc')
                ->orderBy('branch_rec_id', 'asc')
                ->get();
        }

        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->map(function ($branchrec) {
            return $branchrec->groupBy(function ($branch) {
                return $branch->branch_rec_id;
            });
        });

        $order_groups = $order_groups->all();

        $order_date = $order_groups;


        return view('reports.orderreportbybranchrec', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }

    public function report_5($branch, $from, $to)
    {
        $report_title = 'รายงานรายการยกเลิกใบรับส่ง';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);


        $order = Order_header::where('branch_id', $branch)
            ->join('order_statuses', function ($join) {
                $join->on('order_headers.id', '=', 'order_statuses.order_header_id')
                    ->where('order_statuses.status', '=', 'cancel');
            })
            ->where('order_statuses.created_at', '>=', $from)
            ->where('order_statuses.created_at', '<=', $to)
            ->orderBy('order_statuses.created_at', 'asc')
            ->get();


        $order_groups = $order->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcancelbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }

    public function report_6($branch, $from, $to)
    {
        $report_title = 'รายงานขายสดประจำวันแบบสรุป';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);
        $order = Order_header::where('branch_id', $branch)
            ->where('order_header_date', '>=', $from)
            ->where('order_header_date', '<=', $to)
            ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
            ->whereIn('paymenttype', ['H', 'T'])
            ->orderBy('branch_id', 'asc')
            ->orderBy('order_header_no', 'asc')
            ->get();

        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcashsumbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }
    public function report_7($branch, $from, $to)
    {
        $report_title = 'รายงานขายสดประจำวันแบบแสดงรายการ';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);
        $order = Order_header::where('branch_id', $branch)
            ->where('order_header_date', '>=', $from)
            ->where('order_header_date', '<=', $to)
            ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
            ->whereIn('paymenttype', ['H', 'T'])
            ->orderBy('branch_id', 'asc')
            ->orderBy('order_header_no', 'asc')
            ->get();

        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcashdetailbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }

    public function report_8($branch, $from, $to, $artype)
    {
        $report_title = 'รายงานขายเชื่อประจำวันแบบสรุป';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);
        if ($artype == 'true') {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->whereIn('paymenttype', ['F', 'L'])
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        } else {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->whereIn('paymenttype', ['E', 'F', 'L'])
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        }

        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcrsumbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }
    public function report_9($branch, $from, $to, $artype)
    {
        $report_title = 'รายงานขายเชื่อประจำวันแบบแสดงรายการ';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);
        if ($artype == 'true') {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->whereIn('paymenttype', ['F', 'L'])
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        } else {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->whereIn('paymenttype', ['E', 'F', 'L'])
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        }
        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcrdetailbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }
}
