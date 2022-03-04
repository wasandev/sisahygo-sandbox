<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Order_banktransfer;
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
                if ($order->order_type == 'charter') {
                    return view('documents.printorder_charter_head', compact('order', 'order_detail', 'company'));
                } else {
                    return view('documents.printorder_receipt_head', compact('order', 'order_detail', 'company'));
                }
                break;
            case 'form2':
                if ($order->order_type == 'charter') {
                    return view('documents.printorder_charter', compact('order', 'order_detail', 'company'));
                } else {
                    return view('documents.printorder_receipt', compact('order', 'order_detail', 'company'));
                }
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
                $pdf = PDF::loadView('documents.printorderpdf_receipt', compact('order', 'order_detail', 'company'))
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
                $pdf = PDF::loadView('documents.printorder_receipt', compact('order', 'order_detail', 'company'))
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
            ->whereNotNull('order_header_no')
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
                //->lazyById(100, $column = 'id');
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        } else {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking'])
                //->lazyById(100, $column = 'id');
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
    public function report_2s($branch, $from, $to, $cancelflag)
    {
        $report_title = 'รายงานรายการขนส่งประจำวันแบบสรุป';
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);


        if ($cancelflag == 'true') {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                //->lazyById(100, $column = 'id');
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        } else {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking'])
                //->lazyById(100, $column = 'id');
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        }


        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportbillsumbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to', 'cancelflag'));
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
        $report_title = 'รายงานยอดค่าขนส่งตามสาขาปลายทาง ตามวัน';
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
    public function report_4m($branch, $year)
    {
        $report_title = 'รายงานยอดค่าขนส่งตามสาขาปลายทาง ตามเดือน';
        $company = CompanyProfile::find(1);

        if ($branch != 'all') {
            $branchdata = Branch::find($branch);

            $order = Order_header::where('branch_rec_id', $branch)
                ->whereYear('order_header_date', '=', $year)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->orderBy('order_header_date', 'asc')
                ->orderBy('branch_rec_id', 'asc')
                ->get();
        } else {
            $branchdata = null;
            $order = Order_header::select('order_headers.id', 'order_headers.branch_rec_id', 'order_headers.order_header_date', 'order_headers.order_amount', 'branches.name', 'order_type')
                ->join('branches', 'order_headers.branch_rec_id', 'branches.id')
                ->whereYear('order_header_date', '=', $year)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->orderBy('branch_rec_id', 'asc')
                ->orderBy('order_header_date', 'asc')
                ->get();
        }

        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('m-Y');
        });
        $order_groups = $order_groups->map(function ($branchrec) {
            return $branchrec->groupBy(function ($branch) {
                return $branch->branch_rec_id;
            });
        });

        $order_groups = $order_groups->all();

        $order_date = $order_groups;


        return view('reports.orderreportbybranchrecyear', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'year'));
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
            ->whereDate('order_statuses.created_at', '>=', $from)
            ->whereDate('order_statuses.created_at', '<=', $to)
            ->orderBy('order_statuses.created_at', 'asc')
            ->get();


        $order_groups = $order->groupBy(function ($item) {

            return $item->paymenttype;
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcancelbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to'));
    }

    public function report_6($branch, $paytype, $from, $to, $cancelflag)
    {
        if ($paytype == 'H') {
            $report_title = 'รายงานขายสดประจำวันแบบสรุป (เงินสด) ';
        } else {
            $report_title = 'รายงานขายสดประจำวันแบบสรุป (เงินโอน) ';
        }
        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);
        if ($cancelflag == 'true') {


            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->where('paymenttype', $paytype)
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        } else {

            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking'])
                ->where('paymenttype', $paytype)
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        }

        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcashsumbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to', 'paytype', 'cancelflag'));
    }
    public function report_7($branch, $paytype, $from, $to, $cancelflag)
    {
        if ($paytype == 'H') {
            $report_title = 'รายงานขายสดประจำวันแบบแสดงรายการ (เงินสด)';
        } else {
            $report_title = 'รายงานขายสดประจำวันแบบแสดงรายการ (เงินโอน)';
        }

        $company = CompanyProfile::find(1);

        $branchdata = Branch::find($branch);
        if ($cancelflag == 'true') {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                ->where('paymenttype', $paytype)
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        } else {
            $order = Order_header::where('branch_id', $branch)
                ->where('order_header_date', '>=', $from)
                ->where('order_header_date', '<=', $to)
                ->whereNotIn('order_status', ['new', 'checking'])
                ->where('paymenttype', $paytype)
                ->orderBy('branch_id', 'asc')
                ->orderBy('order_header_no', 'asc')
                ->get();
        }
        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcashdetailbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to', 'paytype', 'cancelflag'));
    }

    public function report_8($branch, $from, $to, $artype, $cancelflag)
    {
        $report_title = 'รายงานขายเชื่อประจำวันแบบสรุป';
        $company = CompanyProfile::find(1);
        $branchdata = Branch::find($branch);

        if ($cancelflag == "true") {

            if ($artype == 'F') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->where('paymenttype', '=', 'F')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'L') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->where('paymenttype', '=',  'L')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'E') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->where('paymenttype', '=', 'E')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } else {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->whereIn('paymenttype', ['F', 'L', 'E'])
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            }
        } else {
            if ($artype == 'F') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->where('paymenttype', '=', 'F')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'L') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->where('paymenttype', '=',  'L')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'E') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->where('paymenttype', '=', 'E')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } else {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->whereIn('paymenttype', ['F', 'L', 'E'])
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            }
        }

        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcrsumbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata', 'from', 'to', 'artype', 'cancelflag'));
    }
    public function report_9($branch, $from, $to, $artype, $cancelflag)
    {
        $report_title = 'รายงานขายเชื่อประจำวันแบบแสดงรายการ';
        $company = CompanyProfile::find(1);
        $branchdata = Branch::find($branch);

        if ($cancelflag == 'true') {

            if ($artype == 'F') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->where('paymenttype', '=', 'F')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'L') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->where('paymenttype', '=',  'L')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'E') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->where('paymenttype', '=', 'L')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'A') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking', 'cancel'])
                    ->whereIn('paymenttype', ['F', 'L', 'E'])
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            }
        } else {
            if ($artype == 'F') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->where('paymenttype', '=', 'F')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'L') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->where('paymenttype', '=',  'L')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'E') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->where('paymenttype', '=', 'L')
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            } elseif ($artype == 'A') {
                $order = Order_header::where('branch_id', $branch)
                    ->where('order_header_date', '>=', $from)
                    ->where('order_header_date', '<=', $to)
                    ->whereNotIn('order_status', ['new', 'checking'])
                    ->whereIn('paymenttype', ['F', 'L', 'E'])
                    ->orderBy('branch_id', 'asc')
                    ->orderBy('order_header_no', 'asc')
                    ->get();
            }
        }
        $order_groups = $order->groupBy(function ($item) {
            return $item->order_header_date->format('Y-m-d');
        });
        $order_groups = $order_groups->all();
        $order_date = $order_groups;
        return view('reports.orderreportcrdetailbydate', compact('company', 'report_title', 'order', 'order_date', 'branchdata',  'from', 'to', 'artype', 'cancelflag'));
    }
    public function report_t1($from)
    {
        $report_title = 'รายงานรายการโอนเงินค่าขนส่ง';
        $company = CompanyProfile::find(1);



        $ordertransfer = Order_banktransfer::whereDate('created_at', '=', $from)
            ->orderBy('order_header_id', 'asc')
            ->get();

        $transfer_groups = $ordertransfer->groupBy(function ($item) {
            return $item->transfer_type;
        });
        $transfer_groups = $transfer_groups->all();
        $transfer_type = $transfer_groups;
        return view('reports.orderbanktransferbydate', compact('company', 'report_title', 'ordertransfer', 'transfer_type', 'from'));
    }

    public function report_s1($branch, $to)
    {
        $report_title = 'รายงานสินค้าค้างส่งต้นทาง';
        $company = CompanyProfile::find(1);



        $orders = Order_header::join('customers as b', 'b.id', '=', 'order_headers.customer_rec_id')
            ->where('order_headers.branch_id', $branch)
            ->whereDate('order_headers.order_header_date', '<=', $to)
            ->where('order_headers.order_status', 'confirmed')
            ->orderBy('order_headers.branch_rec_id', 'asc')
            ->orderBy('b.district', 'asc')
            ->orderBy('order_headers.id', 'asc')
            ->get();

        $order_groups = $orders->groupBy([function ($item) {
            return $item->branch_rec_id;
        }, 'b.district']);

        $order_groups = $order_groups->all();

        return view('reports.orderbranchnotload', compact('company', 'report_title', 'orders', 'order_groups', 'branch', 'to'));
    }
}
