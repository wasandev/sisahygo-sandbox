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
    public function report_r1($branch, $from, $to)
    {

        $report_title = 'รายงานภาษีถูกหัก ณ ที่จ่าย';
        $company = CompanyProfile::find(1);
        if ($branch == 'all') {
            $receipts = Receipt_all::whereDate('receipt_date', '>=', $from)
                ->whereDate('receipt_date', '<=', $to)
                ->where('receipts.status','=', '1')
                ->where('receipts.tax_amount' ,'>',0)
                ->orderBy('receipt_date', 'asc')
                ->orderBy('receipttype', 'asc')
                ->orderBy('branch_id', 'asc')
                ->get();
        } else {

            $receipts = Receipt_all::whereDate('receipt_date', '>=', $from)
                ->whereDate('receipt_date', '<=', $to)
                ->where('receipts.branch_id','=',$branch)
                ->where('receipts.status','=', '1')
                ->where('receipts.tax_amount' ,'>',0)
                ->orderBy('receipt_date', 'asc')
                ->orderBy('branch_id', 'asc')
                ->orderBy('receipttype', 'asc')
                
                ->get();
        }

        $tax_groups = $receipts->all();

        $tax_groups = $receipts->groupBy([
            function ($item) {
                return $item->receipt_date->format('Y-m-d') ;
            },
            'branch_id','receipttype'
           
        ]);



        $tax_groups = $tax_groups->all();

        return view('reports.taxwhcustomer', compact('company', 'report_title', 'receipts', 'tax_groups', 'branch', 'from', 'to'));
    }

    public function report_r2($branch, $from, $to)
    {

        $report_title = 'รายงานส่วนลดค่าขนส่ง';
        $company = CompanyProfile::find(1);
        if ($branch == 'all') {
            $receipts = Receipt_all::whereDate('receipt_date', '>=', $from)
                ->whereDate('receipt_date', '<=', $to)
                ->where('receipts.status','=', '1')
                ->where('receipts.discount_amount' ,'>',0)
                ->orderBy('receipt_date', 'asc')
                ->orderBy('receipttype', 'asc')
                ->orderBy('branch_id', 'asc')
                ->get();
        } else {

            $receipts = Receipt_all::whereDate('receipt_date', '>=', $from)
                ->whereDate('receipt_date', '<=', $to)
                ->where('receipts.branch_id','=',$branch)
                ->where('receipts.status','=', '1')
                ->where('receipts.discount_amount' ,'>',0)
                ->orderBy('receipt_date', 'asc')
                ->orderBy('branch_id', 'asc')
                ->orderBy('receipttype', 'asc')
                
                ->get();
        }

        $tax_groups = $receipts->all();

        $tax_groups = $receipts->groupBy([
            function ($item) {
                return $item->receipt_date->format('Y-m-d') ;
            },
            'branch_id','receipttype'
           
        ]);



        $tax_groups = $tax_groups->all();

        return view('reports.discountcustomer', compact('company', 'report_title', 'receipts', 'tax_groups', 'branch', 'from', 'to'));
    }
}
