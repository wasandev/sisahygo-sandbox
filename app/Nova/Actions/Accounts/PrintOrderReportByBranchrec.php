<?php

namespace App\Nova\Actions\Accounts;

use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;

class PrintOrderReportByBranchrec extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-order-report-by-branchrec';
    }
    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'พิมพ์รายงาน';
    }


    public function handle(ActionFields $fields, Collection $models)
    {
        $decodedFilters = collect(json_decode(base64_decode($this->filter), true));
        $branch = $decodedFilters->firstWhere('class', 'App\Nova\Filters\Branch');

        $branch_value = Arr::get($branch, 'value');

        if ($branch_value == '') {
            $branch_value = 'all';
        }
        if ($fields->report_type == false) {
            $from  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\OrderFromDate');
            $from_value = Arr::get($from, 'value');
            if ($from_value == '') {
                return Action::danger('เลือก วันที่เริ่มต้น ที่ต้องการที่เมนูกรองข้อมูลก่อน');
            }
            $to  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\OrderToDate');
            $to_value = Arr::get($to, 'value');
            if ($to_value == '') {
                return Action::danger('เลือก วันที่สิ้นสุด ที่ต้องการที่เมนูกรองข้อมูลก่อน');
            }
            return Action::openInNewTab('/orderheader/report_4/' . $branch_value . '/' . $from_value . '/' . $to_value);
        } else {
            $year_value = strval($fields->report_year);

            return Action::openInNewTab('/orderheader/report_4m/' . $branch_value . '/' . $year_value);
        }
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Boolean::make('ออกรายงานแบบสรุปรายเดือน', 'report_type'),
            NovaDependencyContainer::make([
                Number::make('ระบุปีที่ต้องการออกรายงาน', 'report_year')
                    ->step('1')
                    ->default(function () {
                        return date("Y", strtotime(today()));
                    }),
            ])->dependsOn('report_type', true),
        ];
    }
}
