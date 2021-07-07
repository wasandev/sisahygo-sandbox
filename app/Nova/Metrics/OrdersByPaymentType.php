<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use App\Models\Order_header;

class OrdersByPaymentType extends Partition
{
    public function name()
    {
        return 'ค่าขนส่งตามประเภทการชำระเงิน';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, Order_header::whereNotIn('order_status', ['checking', 'new', 'cancel']), 'order_amount', 'paymenttype')
            ->label(function ($value) {

                switch ($value) {
                    case 'H':
                        return 'เงินสดต้นทาง';
                    case 'E':
                        return 'เงินสดปลายทาง';
                    case 'F':
                        return 'วางบิลต้นทาง';
                    case 'L':
                        return 'วางบิลปลายทาง';
                    case 'T':
                        return 'เงินโอน';
                }
            });
    }



    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'orders-by-payment-type';
    }
}
