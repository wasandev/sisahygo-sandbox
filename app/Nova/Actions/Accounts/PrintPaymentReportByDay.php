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

        if ($fields->type == '1') {

            $branch_value = $fields->branch;
            return Action::openInNewTab('/car/report_c25/' . $branch_value . '/' . $from_value . '/' . $to_value);
        } elseif ($fields->type == '2') {
            $type_str = 'B';
            return Action::openInNewTab('/car/report_11/' . $from_value . '/' . $to_value . '/' . $type_str);
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
            Select::make('เลือกประเภทรายงาน', 'type')
                ->options([
                    '1' => 'รายงานจ่ายเงินรถจากรายการเก็บปลายทางตามสาขา',
                    '2' => 'รายงานจ่ายเงินรถจากรายการเก็บปลายทางรวมทั้งหมด',
                    '3' => 'รายงานจ่ายเงินรถทั้งหมด',
                ])->displayUsingLabels()
                ->rules('required'),

            NovaDependencyContainer::make(
                [
                    Select::make('เลือกสาขา', 'branch')
                        ->options($branches)
                        ->searchable()
                        ->help('เว้นว่างไว้ หากต้องการพิมพ์รายการใบสำคัญจ่าย ที่ไม่ใช่รายการเก็บปลายทาง'),
                ]
            )->dependsOn('type', '1'),

            Date::make('จากวันที่', 'from')
                ->rules('required'),
            Date::make('ถึงวันที่', 'to')
                ->rules('required')

        ];
    }
}
