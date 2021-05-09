<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\CarByType;
use App\Nova\Metrics\CarOwnerType;
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
        return 'Track dashboard';
    }
}
