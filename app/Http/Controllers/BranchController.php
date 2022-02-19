<?php

namespace App\Http\Controllers;


use App\Models\Branch;
use App\Models\Branch_balance;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }


    public function report_20($branch, $from, $to)
    {
        $report_title = 'รายงานตั้งหนี้ลูกหนี้สาขา';
        $company = CompanyProfile::find(1);
        $branchdata = Branch::find($branch);


        $branch_balances = Branch_balance::where('branch_id', $branch)
            ->where('branchbal_date', '>=', $from)
            ->where('branchbal_date', '<=', $to)
            ->orderBy('branch_id', 'asc')
            ->orderBy('branchbal_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();


        $branch_groups = $branch_balances->groupBy(
            function ($item) {
                return $item->branchbal_date->format('Y-m-d');
            }
        );
        $branch_groups = $branch_groups->all();

        return view('reports.branchbalancereport', compact('company', 'report_title', 'branch_balances', 'branch_groups',  'branchdata', 'from', 'to'));
    }
    public function report_21($branch, $from, $to)
    {
        $report_title = 'รายงานรับชำระหนี้ลูกหนี้สาขา';
        $company = CompanyProfile::find(1);
        $branchdata = Branch::find($branch);


        $branch_balances = Branch_balance::select('branch_balances.*', 'receipts.branchpay_by as branchpay_by')
            ->leftjoin('receipts', 'branch_balances.receipt_id', '=', 'receipts.id')
            ->where('branch_balances.branch_id', $branch)
            //->where('branch_balances.branchbal_date', '>=', $from)
            //->where('branch_balances.branchbal_date', '<=', $to)
            ->where('branch_balances.branchpay_date', '>=', $from)
            ->where('branch_balances.branchpay_date', '<=', $to)
            ->where('branch_balances.payment_status', '=', true)
            ->orderBy('branch_balances.branchpay_date', 'asc')
            ->orderBy('receipts.branchpay_by', 'asc')
            ->orderBy('branch_balances.order_header_id', 'asc')
            ->get();



        $branch_groups = $branch_balances->groupBy([


            function ($item) {
                return $item->branchpay_date->format('Y-m-d');
            },
            function ($item) {
                return $item->branchpay_by;
            },

        ]);

        $branch_groups = $branch_groups->all();

        return view('reports.branchbalancereceipt', compact('company', 'report_title', 'branch_balances', 'branch_groups',  'branchdata', 'from', 'to'));
    }
    public function report_22($branch, $to)
    {
        $report_title = 'รายงานลูกหนี้สาขาค้างชำระ';
        $company = CompanyProfile::find(1);
        $branchdata = Branch::find($branch);




        $branch_balances1 = Branch_balance::where('branch_id', $branch)
            ->where('branchbal_date', '<=', $to)
            ->where('branchpay_date', '>', $to)
            ->orderBy('branchbal_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $branch_balances2 = Branch_balance::where('branch_id', $branch)
            ->where('branchbal_date', '<=', $to)
            ->whereNull('branchpay_date')
            ->orderBy('branchbal_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $branch_balances = $branch_balances1->concat($branch_balances2);
        $branch_groups = $branch_balances->groupBy(
            function ($item) {
                return $item->branchbal_date->format('Y-m-d');
            }
        );
        $branch_groups = $branch_groups->all();


        return view('reports.branchbalancesummary', compact('company', 'report_title', 'branch_balances', 'branch_groups',  'branchdata', 'to'));
    }
}
