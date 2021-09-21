<?php

namespace App\Nova\Metrics;

use App\Models\Car;
use App\Models\Cartype;
use App\Models\Order_header;
use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class OrderByCheckerPartition extends Partition
{
    public function name()
    {
        return 'จำนวนใบรับส่งตามพนักงานตรวจรับ';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order_header::where('order_status', '<>', 'new'),  'checker_id')
            ->label(function ($value) {
                $user = User::find($value);
                return $user->name;
            });
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
        return 'order-by-checker-partition';
    }
}
