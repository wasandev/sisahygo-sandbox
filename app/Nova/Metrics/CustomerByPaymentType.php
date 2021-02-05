<?php

namespace App\Nova\Metrics;

use App\Models\Customer;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class CustomerByPaymentType extends Partition
{
    public function name()
    {
        return 'เงื่อนไขการชำระเงิน';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Customer::class,  'paymenttype')
            ->label(function ($value) {


                switch ($value) {
                    case 'H':
                        return 'เงินสดต้นทาง';
                    case 'E':
                        return 'เงินสดปลายทาง';
                    case 'Y':
                        return 'วางบิล';
                }
            });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    // public function cacheFor()
    // {
    //     return now()->addMinutes(5);
    // }


    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'customer-by-payment-type';
    }
}
