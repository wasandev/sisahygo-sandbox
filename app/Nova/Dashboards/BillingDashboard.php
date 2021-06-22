<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\OrderbyUser;
use App\Nova\Metrics\OrderCashbyUser;
use App\Nova\Metrics\OrderIncomes;
use App\Nova\Metrics\OrdersByBranchRec;
use App\Nova\Metrics\OrdersByPaymentType;
use App\Nova\Metrics\OrdersPerMonth;
use Laravel\Nova\Dashboard;
use Wasandev\Orderstatus\Orderstatus;

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
            (new OrderCashbyUser())->width('1/2'),
            (new OrderIncomes())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view order-incomes');
                }),
            (new OrdersPerMonth())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view orders-per-day');
                }),
            (new OrdersByPaymentType())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view orders-by-payment-type');
                }),
            (new OrdersByBranchRec())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view orders-by-payment-type');
                }),
            (new  Orderstatus())

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
        return 'Billing dashboard';
    }
}
