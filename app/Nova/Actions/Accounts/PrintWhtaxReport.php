<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PrintWhtaxReport extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-whtax-report';
    }
    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'รายงานสรุปภาษีหัก ณ ที่จ่าย';
    }


    public function handle(ActionFields $fields, Collection $models)
    {
        $decodedFilters = collect(json_decode(base64_decode($this->filter), true));



        $from  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\WhtaxFromDate');

        $from_value = Arr::get($from, 'value');
        if ($from_value == '') {
            return Action::danger('เลือก วันที่เริ่มต้น ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }

        $to  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\WhtaxToDate');
        $to_value = Arr::get($to, 'value');
        if ($to_value == '') {
            return Action::danger('เลือก วันที่สิ้นสุด ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }

        $whtaxtype  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\WhtaxType');

        $whtaxtype_value = Arr::get($whtaxtype, 'value');

        if ($whtaxtype_value == '') {
            return Action::danger('เลือก ประเภทแบบ ภงด. ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }
        return Action::openInNewTab('/car/report_24/' . $from_value . '/' . $to_value . '/' . $whtaxtype_value);
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
