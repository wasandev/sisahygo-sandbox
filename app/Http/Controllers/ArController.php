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

    public function report_17($branch, $customer, $to)
    {
        $report_title = 'รายงานลูกหนี้ค้างชำระ';
        $company = CompanyProfile::find(1);
        if ($customer == 'all') {
            if ($branch == 'all') {
                $branchdata = null;
               // $aroutstandings 
               $arnotpay_1 = Ar_balance::select('ar_balances.*') 
                    ->join('customers','customers.id','=','ar_balances.customer_id')                                     
                    ->where('ar_balances.docdate', '<=', $to)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->whereNull('ar_balances.receipt_id')                  
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
                $arnotpay_2 = Ar_balance::select('ar_balances.*') 
                    ->join('receipts', 'receipts.id', '=', 'ar_balances.receipt_id') 
                    ->join('customers','customers.id','=','ar_balances.customer_id')                                      
                    ->where('ar_balances.docdate', '<=', $to)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->where('receipts.receipt_date' ,'>',$to)                    
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();

                $aroutstandings = $arnotpay_1->merge($arnotpay_2);
            } else {
                $branchdata = Branch::find($branch);
                $arnotpay_b1 = Ar_balance::select('ar_balances.*') 
                    ->join('customers','customers.id','=','ar_balances.customer_id')                       
                    ->where('ar_balances.branch_id', '=', $branch)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->where('ar_balances.docdate', '<=', $to)
                    ->whereNull('ar_balances.receipt_id')                    
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
                $arnotpay_b2 = Ar_balance::select('ar_balances.*') 
                    ->join('receipts', 'receipts.id', '=', 'ar_balances.receipt_id')
                    ->join('customers','customers.id','=','ar_balances.customer_id')  
                    ->where('ar_balances.branch_id', '=', $branch)                                    
                    ->where('ar_balances.docdate', '<=', $to)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->where('receipts.receipt_date' ,'>',$to)                    
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
                $aroutstandings = $arnotpay_b1->merge($arnotpay_b2);
            }
        } else {
            if ($branch == 'all') {
                $branchdata = null;
                $arnotpay_c1  = Ar_balance::select('ar_balances.*')
                    ->join('customers','customers.id','=','ar_balances.customer_id')
                    ->where('ar_balances.customer_id', '=', $customer)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->whereNull('ar_balances.receipt_id')   
                    ->where('ar_balances.docdate', '<=', $to)
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
                $arnotpay_c2 = Ar_balance::select('ar_balances.*') 
                    ->join('receipts', 'receipts.id', '=', 'ar_balances.receipt_id') 
                    ->join('customers', 'customers.id', '=', 'ar_balances.customer_id')
                    ->where('ar_balances.customer_id', '=', $customer)            
                    ->where('ar_balances.docdate', '<=', $to)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->where('receipts.receipt_date' ,'>',$to)                    
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
                $aroutstandings = $arnotpay_c1->merge($arnotpay_c2);
                
            } else {
                $branchdata = Branch::find($branch);
                $arnotpay_cb1 = Ar_balance::select('ar_balances.*')
                    ->join('customers', 'customers.id', '=', 'ar_balances.customer_id')
                    ->where('ar_balances.branch_id', '=', $branch)
                    ->where('ar_balances.customer_id', '=', $customer)
                    ->where('ar_balances.docdate', '<=', $to)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->whereNull('ar_balances.receipt_id') 
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();
                $arnotpay_cb2 = Ar_balance::select('ar_balances.*') 
                    ->join('receipts', 'receipts.id', '=', 'ar_balances.receipt_id') 
                    ->join('customers', 'customers.id', '=', 'ar_balances.customer_id')
                    ->where('ar_balances.branch_id', '=', $branch)
                    ->where('ar_balances.customer_id', '=', $customer)            
                    ->where('ar_balances.docdate', '<=', $to)
                    ->where('ar_balances.doctype', '=', 'P')
                    ->where('receipts.receipt_date' ,'>',$to)                    
                    ->orderBy('customers.name','asc')
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->orderBy('ar_balances.id', 'asc')
                    ->get();

                $aroutstandings = $arnotpay_cb1->merge($arnotpay_cb2);
            }
        }

        $ar_groups = $aroutstandings->groupBy(['customer_id']);
        $ar_groups = $ar_groups->all();

        return view('reports.aroutstandingreport', compact('company', 'report_title', 'aroutstandings', 'ar_groups', 'branchdata', 'customer', 'to'));
    }

    public function report_18($branch, $from, $to)
    {
        $report_title = 'รายงานสรุปยอดลูกหนี้';
        $company = CompanyProfile::find(1);
        if ($branch == 'all') {
            $branchdata = null;
            $ar_balances = Ar_balance::where('ar_balances.docdate', '<=', $to)
                ->join('customers', 'customers.id', '=', 'ar_balances.customer_id')
                ->orderBy('customers.name', 'asc')
                ->get();
        } else {
            $branchdata = Branch::find($branch);
            $ar_balances = Ar_balance::where('ar_balances.docdate', '<=', $to)
                ->join('customers', 'customers.id', '=', 'ar_balances.customer_id')
                ->where('ar_balances.branch_id', '=', $branch)
                ->orderBy('customers.name', 'asc')
                ->get();
        }

        $ar_groups = $ar_balances->groupBy(['customer_id']);
        $ar_groups = $ar_groups->all();

        return view('reports.arsummaryreport', compact('company', 'report_title', 'ar_balances', 'ar_groups', 'branchdata', 'from', 'to'));
    }

    public function report_19($branch, $customer, $from, $to)
    {
        $report_title = 'รายงานรับชำระหนี้ลูกหนี้การค้า';
        $company = CompanyProfile::find(1);
        if ($customer == 'all') {
            if ($branch == 'all') {
                $branchdata = null;
                $ar_receipts = Ar_balance::join('receipts', 'ar_balances.receipt_id', 'receipts.id')
                    ->where('ar_balances.doctype', '=', 'R')
                    ->where('ar_balances.docdate', '>=', $from)
                    ->where('ar_balances.docdate', '<=', $to)
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->get();
            } else {
                $branchdata = Branch::find($branch);
                $ar_receipts = Ar_balance::join('receipts', 'ar_balances.receipt_id', 'receipts.id')
                    ->where('ar_balances.doctype', '=', 'R')
                    ->where('receipts.branch_id', '=', $branch)
                    ->where('ar_balances.docdate', '>=', $from)
                    ->where('ar_balances.docdate', '<=', $to)
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->get();
            }
        } else {
            if ($branch == 'all') {
                $branchdata = null;
                $ar_receipts = Ar_balance::join('receipts', 'ar_balances.receipt_id', 'receipts.id')
                    ->where('ar_balances.customer_id', '=', $customer)
                    ->where('ar_balances.doctype', '=', 'R')
                    ->where('ar_balances.docdate', '>=', $from)
                    ->where('ar_balances.docdate', '<=', $to)
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->get();
            } else {
                $branchdata = Branch::find($branch);
                $ar_receipts = Ar_balance::join('receipts', 'ar_balances.receipt_id', 'receipts.id')
                    ->where('ar_balances.customer_id', '=', $customer)
                    ->where('ar_balances.doctype', '=', 'R')
                    ->where('ar_balances.docdate', '>=', $from)
                    ->where('ar_balances.docdate', '<=', $to)
                    ->where('receipts.branch_id', '=', $branch)
                    ->orderBy('ar_balances.docdate', 'asc')
                    ->get();
            }
        }

        $receipt_groups = $ar_receipts->groupBy([function ($item) {
            return $item->docdate->format('Y-m-d');
        }, 'customer_id']);
        $receipts_date = $receipt_groups->all();

        return view('reports.arreceiptreport', compact('company', 'report_title', 'ar_receipts', 'receipts_date', 'branchdata', 'customer', 'from', 'to'));
    }
}
