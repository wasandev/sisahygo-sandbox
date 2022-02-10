<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;

use function PHPUnit\Framework\isNull;

class PrintPaymentReportByDay extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-payment-report-by-day';
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


        $from_value = $fields->from;
        $to_value = $fields->to;

        if ($fields->type_value) {
            $type_str = 'B';
            $branch_value = $fields->branch;
            return Action::openInNewTab('/car/report_c25/' . $branch_value . '/' . $from_value . '/' . $to_value);
        } else {
            $type_str = 'all';
            return Action::openInNewTab('/car/report_11/' . $from_value . '/' . $to_value . '/' . $type_str);
        }
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $branches = \App\Models\Branch::where('type', 'partner')->pluck('name', 'id');
        return [
            Boolean::make('เฉพาะรายการจ่ายจากยอดเก็บปลายทางสาขา Partner', 'type_value'),
            Select::make('เลือกสาขา', 'branch')
                ->options($branches)
                ->searchable()
                ->help('เว้นว่างไว้ หากต้องการพิมพ์รายการใบสำคัญจ่าย ที่ไม่ใช่รายการเก็บปลายทาง'),
            Date::make('วันที่เริ่มต้น', 'from')
                ->rules('required'),
            Date::make('วันที่สิ้นสุด', 'to')
                ->rules('required')

        ];
    }
}
