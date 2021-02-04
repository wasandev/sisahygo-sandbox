<?php

namespace App\Nova\Metrics;

use App\Models\Employee;
use App\Models\Department;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class EmployeeByDept extends Partition
{
    public function name()
    {
        return 'พนักงานตามฝ่าย';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Employee::class,  'department_id')
            ->label(function ($value) {
                $department = Department::find($value);
                if (isset($department)) {
                    switch ($department->name) {
                        case null:
                            return 'None';
                        default:
                            return ucfirst($department->name);
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
        return 'employee-by-dept';
    }
}
