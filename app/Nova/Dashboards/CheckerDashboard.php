<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\CheckerbyUser;
use App\Nova\Metrics\CheckerbyUserMetric;
use App\Nova\Metrics\CheckerCancelbyUser;
use App\Nova\Metrics\CheckerProblembyUser;
use Laravel\Nova\Dashboard;
use Wasandev\Orderstatus\Orderstatus;

class CheckerDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new CheckerbyUserMetric())->width('1/3'),
            (new CheckerCancelbyUser())->width('1/3'),
            (new CheckerProblembyUser())->width('1/3'),
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
        return 'checker-dashboard';
    }
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'Checker dashboard';
    }
}
