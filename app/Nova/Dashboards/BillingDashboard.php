<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\OrderbyUser;
use App\Nova\Metrics\OrderCashbyUser;
use Laravel\Nova\Dashboard;


class BillingDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new OrderbyUser())->width('1/2'),
            (new OrderCashbyUser())->width('1/2')
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'billing-dashboard';
    }
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'พนักงานออกเอกสาร';
    }
}
