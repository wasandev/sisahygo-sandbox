<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\CharterIncomes;
use App\Nova\Metrics\CustomersByDistrict;
use App\Nova\Metrics\CustomersByProvince;
use App\Nova\Metrics\CustomersPerDay;
use App\Nova\Metrics\ExpressIncomes;
use App\Nova\Metrics\NewCustomers;
use App\Nova\Metrics\OrderAllIncomes;
use App\Nova\Metrics\OrderIncomes;
use App\Nova\Metrics\OrdersByBranchRec;
use App\Nova\Metrics\OrdersByPaymentType;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\OrdersPerMonth;
use App\Nova\Metrics\WaybillAmount;
use App\Nova\Metrics\WaybillAmountPerDay;
use App\Nova\Metrics\WaybillIncome;
use App\Nova\Metrics\WaybillIncomePerDay;
use App\Nova\Metrics\WaybillLoading;
use App\Nova\Metrics\WaybillPayable;
use App\Nova\Metrics\WaybillPayablePerDay;
use App\Nova\Metrics\WaybillsPerDay;
use Laravel\Nova\Dashboard;
use Wasandev\Orderstatus\Orderstatus;

class MkDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [

            (new OrderAllIncomes())->width('1/2'),
            (new OrderIncomes())->width('1/2'),
            (new ExpressIncomes())->width('1/2'),
            (new CharterIncomes())->width('1/2'),

            (new OrdersPerMonth())->width('1/2'),
            (new OrdersPerDay())->width('1/2'),

            (new OrdersByPaymentType())->width('1/2'),
            (new OrdersByBranchRec())->width('1/2'),

            (new WaybillsPerDay())->width('1/2'),
            (new WaybillLoading())->width('1/2'),

            (new WaybillAmountPerDay())->width('1/2'),
            (new WaybillPayablePerDay())->width('1/2'),

            //(new WaybillIncome())->width('1/2'),
            (new WaybillIncomePerDay())->width('1/2'),

            (new CustomersPerDay())->width('1/2'),
            (new NewCustomers())->width('1/2'),
            (new CustomersByProvince())->width('1/2'),
            (new CustomersByDistrict())->width('1/2'),



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
        return 'mk-dashboard';
    }
    public static function label()
    {
        return 'ข้อมูลสรุปฝ่ายบริการ';
    }
}
