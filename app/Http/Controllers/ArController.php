<?php

namespace App\Http\Controllers;

use App\Models\Ar_customer;
use App\Models\Ar_balance;
use App\Models\Branch;
use App\Models\CompanyProfile;
use App\Models\Receipt_ar;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ArController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }


    public function report_16($customer, $from, $to)
    {
        $report_title = 'รายงานบัญชีคุมลูกหนี้';
        $company = CompanyProfile::find(1);
        $ardata = Ar_customer::find($customer);

        $payforword = Ar_balance::where('customer_id', $customer)
            ->where('docdate', '<', $from)
            ->where('doctype', '=', 'P')
            ->sum('ar_amount');
        $recforword = Ar_balance::where('customer_id', $customer)
            ->where('docdate', '<', $from)
            ->where('doctype', '=', 'R')
            ->sum('ar_amount');
        $arcards = Ar_balance::where('customer_id', $customer)
            ->where('docdate', '>=', $from)
            ->where('docdate', '<=', $to)
            ->orderBy('docdate', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('reports.arcardreport', compact('company', 'report_title', 'arcards', 'recforword', 'payforword', 'ardata', 'from', 'to'));
    }

    public function report_17($customer, $to)
    {
        $report_title = 'รายงานลูกหนี้ค้างชำระ';
        $company = CompanyProfile::find(1);
        if ($customer == 'all') {
            $aroutstandings = Ar_balance::join('order_headers', 'ar_balances.order_header_id', 'order_headers.id')
                ->where('ar_balances.docdate', '<=', $to)
                ->where('order_headers.payment_status', '=', 'false')
                ->orderBy('ar_balances.docdate', 'asc')
                ->orderBy('ar_balances.id', 'asc')
                ->get();
        } else {
            $aroutstandings = Ar_balance::join('order_headers', 'ar_balances.order_header_id', 'order_headers.id')
                ->where('ar_balances.customer_id', '=', $customer)
                ->where('ar_balances.docdate', '<=', $to)
                ->where('order_headers.payment_status', '=', 'false')
                ->orderBy('ar_balances.docdate', 'asc')
                ->orderBy('ar_balances.id', 'asc')
                ->get();
        }

        $ar_groups = $aroutstandings->groupBy(['customer_id']);
        $ar_groups = $ar_groups->all();

        return view('reports.aroutstandingreport', compact('company', 'report_title', 'aroutstandings', 'ar_groups', 'customer', 'to'));
    }

    public function report_18($from, $to)
    {
        $report_title = 'รายงานสรุปยอดลูกหนี้';
        $company = CompanyProfile::find(1);

        $ar_balances = Ar_balance::where('ar_balances.docdate', '>=', $from)
            ->where('ar_balances.docdate', '<=', $to)
            ->orderBy('ar_balances.customer_id', 'asc')
            ->get();


        $ar_groups = $ar_balances->groupBy(['customer_id']);
        $ar_groups = $ar_groups->all();

        return view('reports.arsummaryreport', compact('company', 'report_title', 'ar_balances', 'ar_groups', 'from', 'to'));
    }

    public function report_19($branch, $customer, $from, $to)
    {
        $report_title = 'รายงานรับชำระหนี้ลูกหนี้การค้า';
        $company = CompanyProfile::find(1);
        if ($customer == 'all') {
            if ($branch == 'all') {
                $branchdata = null;
                $ar_receipts = Ar_balance::join('receipts', 'ar_balances.receipt_id', 'receipts.id')
                    //->where('ar_balances.doctype', '=', 'R')
                    ->where('ar_balances.docdate', '>=', $from)
                    ->where('ar_balances.docdate', '<=', $to)
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
            } else {
                $branchdata = Branch::find($branch);
                $ar_receipts = Ar_balance::join('receipts', 'ar_balances.receipt_id', 'receipts.id')
                    //->where('ar_balances.doctype', '=', 'R')
                    ->where('ar_balances.branch_id', '=', $branch)
                    ->where('ar_balances.docdate', '>=', $from)
                    ->where('ar_balances.docdate', '<=', $to)
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
            }
        } else {
            $ar_receipts = Ar_customer::join('receipts', 'ar_balances.receipt_id', 'receipts.id')
                ->where('ar_balances.customer_id', '=', $customer)
                //->where('ar_balances.doctype', '=', 'R')
                ->where('ar_balances.docdate', '>=', $from)
                ->where('ar_balances.docdate', '<=', $to)
                ->orderBy('ar_balances.id', 'asc')
                ->get();
        }

        $receipt_groups = $ar_receipts->groupBy([function ($item) {
            return $item->docdate->format('Y-m-d');
        }, 'customer_id']);
        $receipts_date = $receipt_groups->all();

        return view('reports.arreceiptreport', compact('company', 'report_title', 'ar_receipts', 'receipts_date', 'branchdata', 'customer', 'from', 'to'));
    }
}
