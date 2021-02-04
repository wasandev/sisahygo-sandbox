<?php

namespace App\Nova\Metrics;

use App\Models\Branch;
use App\Models\Employee;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class EmployeeByBranch extends Partition
{
    public function name()
    {
        return 'พนักงานตามสาขา';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Employee::class,  'branch_id')
            ->label(function ($value) {
                $branch = Branch::find($value);
                if (isset($branch)) {
                    switch ($branch->name) {
                        case null:
                            return 'None';
                        default:
                            return ucfirst($branch->name);
                    }
                }
            });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */

    public function uriKey()
    {
        return 'employee-by-branch';
    }
}
