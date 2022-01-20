<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\CarPaymentPerDay;
use App\Nova\Metrics\CarReceiptPerDay;
use App\Nova\Metrics\CarReceivePerDay;
use App\Nova\Metrics\OrderBranchNotPayPerDay;
use App\Nova\Metrics\OrderBranchPayPerDay;
use App\Nova\Metrics\OrderBranchPerDay;
use App\Nova\Metrics\OrderCashPerDay;
use App\Nova\Metrics\OrderTransferPerDay;
use App\Nova\Metrics\TaxPaymentPerDay;
use Laravel\Nova\Dashboard;
use Wasandev\Orderstatus\Orderstatus;

class FnDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new OrderBranchPerDay())->width('1/2')->help('แสดงรายการใบรับส่งเก็บเงินปลายทางที่สาขากำหนดรถถึงสาขาแล้ว'),
            (new OrderBranchPayPerDay())->width('1/2'),
            (new OrderBranchNotPayPerDay())->width('1/2'),
            (new OrderCashPerDay())->width('1/2'),
            (new OrderTransferPerDay())->width('1/2'),
            (new CarPaymentPerDay())->width('1/2'),
            (new CarReceivePerDay())->width('1/2'),
            (new TaxPaymentPerDay())->width('1/2')

        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'fn-dashboard';
    }
    public static function label()
    {
        return 'ข้อมูลสรุปการเงิน';
    }
}
