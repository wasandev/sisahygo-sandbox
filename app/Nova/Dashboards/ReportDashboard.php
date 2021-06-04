<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboard;
use Wasandev\Report\Report;

class ReportDashboard extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new Report())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view reportcards');
            }),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'report-dashboard';
    }

    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'รายงาน';
    }
}
