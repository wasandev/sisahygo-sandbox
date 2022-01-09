<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\Branchs\OrderBillBalance;
use App\Nova\Metrics\Branchs\OrderBillPay;
use App\Nova\Metrics\OrderBillPerDay;
use Laravel\Nova\Dashboard;
use Wasandev\Orderstatus\Orderstatus;

class ArDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new OrderBillPerDay())->width('1/2'),
            (new OrderBillPay())->width('1/2'),
            // (new OrderBillBalance())->width('1/2'),
            (new Orderstatus())
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'ar-dashboard';
    }
    public static function label()
    {
        return 'ฝ่ายเร่งรัดฯ';
    }
}
