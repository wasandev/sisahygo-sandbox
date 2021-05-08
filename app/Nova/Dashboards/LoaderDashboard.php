<?php

namespace App\Nova\Dashboards;


use App\Nova\Metrics\LoaderbyUser;
use App\Nova\Metrics\WaybillbyLoader;
use Laravel\Nova\Dashboard;


class LoaderDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new LoaderbyUser())->width('1/2'),
            (new WaybillbyLoader())->width('1/2')
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'loader-dashboard';
    }
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'พนักงานจัดขึ้น';
    }
}
