<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\Branchs\BranchBalance;
use App\Nova\Metrics\Branchs\BranchBalanceDelivery;
use App\Nova\Metrics\Branchs\BranchBalanceNotpay;
use App\Nova\Metrics\Branchs\BranchBalancePay;
use App\Nova\Metrics\Branchs\BranchBalanceWarehouse;
use App\Nova\Metrics\Branchs\BranchOrder;
use App\Nova\Metrics\Branchs\BranchrecWaybill;
use Laravel\Nova\Dashboard;

class BranchDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new BranchOrder())->width('full'),
            (new BranchrecWaybill())->width('1/2'),
            (new BranchBalance())->width('1/2'),
            (new BranchBalancePay())->width('1/2'),
            (new BranchBalanceNotpay())->width('1/2'),
            (new BranchBalanceWarehouse())->width('1/2')->help('รวมทุกประเภทการชำระเงิน'),
            (new BranchBalanceDelivery())->width('1/2')->help('รวมทุกประเภทการชำระเงิน'),


        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'branch-dashboard';
    }
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'Branch dashboard';
    }
}
