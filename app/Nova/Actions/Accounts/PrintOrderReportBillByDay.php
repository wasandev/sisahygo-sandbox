<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PrintOrderReportBillByDay extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-order-report-bill-by-day';
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
            return Action::danger('เลือกสาขาที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }

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
        $cancelflag  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CancelFlag');

        $cancelflag_value = Arr::get($cancelflag, 'value.cancelflag');

        if ($cancelflag_value) {
            $cancelflag_str = 'true';
        } else {
            $cancelflag_str = 'false';
        }
        return Action::openInNewTab('/orderheader/report_2/' . $branch_value . '/' . $from_value . '/' . $to_value . '/' . $cancelflag_str);
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
