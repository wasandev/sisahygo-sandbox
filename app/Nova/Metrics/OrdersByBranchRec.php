<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use App\Models\Order_header;
use App\Models\Branch;

class OrdersByBranchRec extends Partition
{
    public function name()
    {
        return 'ค่าขนส่งตามสาขาปลายทาง';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, Order_header::whereNotIn('order_status', ['checking', 'new', 'cancel']), 'order_amount', 'branch_rec_id')
            ->label(function ($value) {
                $branchrec = Branch::find($value);
                switch ($branchrec->name) {
                    case null:
                        return 'None';
                    default:
                        return ucfirst($branchrec->name);
                }
            });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'orders-by-branch-rec';
    }
}
