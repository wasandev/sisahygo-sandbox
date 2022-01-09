<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\CarByType;
use App\Nova\Metrics\CarOwnerType;
use App\Nova\Metrics\WaybillAmount;
use App\Nova\Metrics\WaybillAmountPerDay;
use App\Nova\Metrics\WaybillIncome;
use App\Nova\Metrics\WaybillIncomePerDay;
use App\Nova\Metrics\WaybillLoading;
use App\Nova\Metrics\WaybillPayable;
use App\Nova\Metrics\WaybillPayablePerDay;
use App\Nova\Metrics\WaybillsPerDay;
use Laravel\Nova\Dashboard;

class TruckDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new CarByType())
                ->width('1/2'),
            (new CarOwnerType())
                ->width('1/2'),
            (new WaybillsPerDay())->width('1/2'),
            (new WaybillLoading())->width('1/2'),

            (new WaybillAmountPerDay())->width('1/2'),
            (new WaybillPayablePerDay())->width('1/2'),

            //(new WaybillIncome())->width('1/2'),
            (new WaybillIncomePerDay())->width('1/2'),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'truck-dashboard';
    }
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'ข้อมูลสรุปรถบรรทุก';
    }
}
