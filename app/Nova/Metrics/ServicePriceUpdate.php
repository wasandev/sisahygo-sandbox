<?php

namespace App\Nova\Metrics;

use App\Models\Productservice_newprice;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use App\Models\Productservice_newprice3;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServicePriceUpdate extends Partition
{
    public function name()
    {
        return '%การปรับราคาตามผู้ใช้';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $partitionResult = $this->count(
            $request,
            Productservice_newprice::whereYear('updated_at', Carbon::now()->year)
                ->whereMonth('updated_at', Carbon::now()->month),
            'updated_by'
        )
            ->label(function ($value) {
                $user = User::find($value);
                if (isset($user)) {
                    return $user->name;
                } else {
                    return '-';
                }
            });
        arsort($partitionResult->value);

        return $partitionResult;
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
        return 'service-price-update';
    }
}
