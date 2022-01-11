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
        //$branchdata = Branch::find($branch);

        if ($branch == 'all') {
            $branch_balances = Branch_balance::where('branchbal_date', '>=', $from)
                ->where('branchbal_date', '<=', $to)
                ->orderBy('branchbal_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $branch_balances = Branch_balance::where('branch_id', $branch)
                ->where('branchbal_date', '>=', $from)
                ->where('branchbal_date', '<=', $to)
                ->orderBy('branchbal_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $branch_groups = $branch_balances->groupBy([
            'branch_id', function ($item) {
                return $item->branchbal_date->format('Y-m-d');
            }
        ]);
        $branch_groups = $branch_groups->all();

        return view('reports.branchbalancereport', compact('company', 'report_title', 'branch_balances', 'branch_groups',  'branch', 'from', 'to'));
    }
    public function report_21($branch, $from, $to)
    {
        $report_title = 'รายงานรับชำระหนี้ลูกหนี้สาขา';
        $company = CompanyProfile::find(1);

        if ($branch == 'all') {
            $branch_balances = Branch_balance::where('branchpay_date', '>=', $from)
                ->where('branchpay_date', '<=', $to)
                ->where('pay_amount', '>', 0)
                ->where('payment_status', '=', true)
                ->orderBy('branchpay_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $branch_balances = Branch_balance::where('branch_id', $branch)
                ->where('branchpay_date', '>=', $from)
                ->where('branchpay_date', '<=', $to)
                ->where('pay_amount', '>', 0)
                ->where('payment_status', '=', true)
                ->orderBy('branchpay_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $branch_groups = $branch_balances->groupBy([
            'branch_id', function ($item) {
                return $item->branchpay_date->format('Y-m-d');
            }
        ]);
        $branch_groups = $branch_groups->all();

        return view('reports.branchbalancereceipt', compact('company', 'report_title', 'branch_balances', 'branch_groups',  'branch', 'from', 'to'));
    }
    public function report_22($branch, $to)
    {
        $report_title = 'รายงานรับลูกหนี้สาขาค้างชำระ';
        $company = CompanyProfile::find(1);

        if ($branch == 'all') {
            $branch_balances = Branch_balance::where('branchbal_date', '<=', $to)
                ->where('pay_amount', '=', 0)
                ->orderBy('branchbal_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $branch_balances = Branch_balance::where('branch_id', $branch)
                ->where('branchbal_date', '<=', $to)
                ->where('pay_amount', '=', 0)
                ->orderBy('branchbal_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $branch_groups = $branch_balances->groupBy([
            'branch_id', function ($item) {
                return $item->branchbal_date->format('Y-m-d');
            }
        ]);
        $branch_groups = $branch_groups->all();

        return view('reports.branchbalancesummary', compact('company', 'report_title', 'branch_balances', 'branch_groups',  'branch', 'to'));
    }
}
