<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;

use function PHPUnit\Framework\isNull;

class PrintArSummaryReport extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-ar-summary-report';
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
        $branch_value = $fields->ar_branch;
        if ($branch_value == '') {
            $branch_value = 'all';
        }
        $from_value = $fields->from;
        $to_value = $fields->to;


        return Action::openInNewTab('/ar/report_18/' . $branch_value . '/' . $from_value . '/' . $to_value);
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $branchs = \App\Models\Branch::pluck('name', 'id');
        return [
            Select::make('เลือกสาขา', 'ar_branch')
                ->options($branchs)
                ->searchable()
                ->help('เลือกสาขาที่ต้องการออกรายงาน หากต้องการออกรายงานทั้งหมดไม่ต้องเลือก'),
            Date::make('วันที่เริ่มต้น', 'from')
                ->rules('required'),
            Date::make('วันที่สิ้นสุด', 'to')
                ->rules('required')
        ];
    }
}
