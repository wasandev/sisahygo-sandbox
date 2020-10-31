<?php

namespace App\Nova\Dashboards;


use Laravel\Nova\Dashboard;
use App\Nova\Metrics\CustomersPerDay;
use App\Nova\Metrics\NewCustomers;
use App\Nova\Metrics\CharterJobsPerDay;

class Sisahygo extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [

            (new CustomersPerDay)->width('1/3'),
            (new CharterJobsPerDay)->width('1/3'),
            (new NewCustomers)->width('1/3'),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'sisahygo';
    }
}
