<?php

namespace App\Nova\Metrics;

use App\Models\Branch_balance;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;

class OrderBranchPayPerDay extends Trend
{
    public $refreshWhenActionRuns = true;
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->sumByDays($request, Branch_balance::join('branches', 'branch_balances.branch_id', 'branches.id')
            ->where('branches.type', '=', 'owner'), 'branch_balances.pay_amount', 'branch_balances.branchpay_date')
            ->showSumValue()
            ->format('0,0.00');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [

            30 => '30 วัน',
            60 => '60 วัน',
            365 => '365 วัน',
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'order-branch-pay-per-day';
    }
    public function name()
    {
        return 'ยอดจัดเก็บเงินปลายทางตามวัน (เฉพาะสาขาบริษัท)';
    }
}
