<?php

namespace App\Nova\Dashboards;

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
        return 'fn-dashboard';
    }
    public static function label()
    {
        return 'Financial dashboard';
    }
}
