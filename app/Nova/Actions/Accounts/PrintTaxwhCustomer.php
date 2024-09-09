<?php

namespace App\Nova\Actions\Accounts;

use App\Models\Receipt_all;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;

class PrintTaxwhCustomer extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-taxwh-customer';
    }
    public function name()
    {
        return 'พิมพ์รายงาน';
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $decodedFilters = collect(json_decode(base64_decode($this->filter), true));
        $branch = $decodedFilters->firstWhere('class', 'App\Nova\Filters\Branch');

        $branch_value = Arr::get($branch, 'value');

        if ($branch_value == '') {
            $branch_value = 'all';
        }

        $from  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\ReceiptFromDate');
        $from_value = Arr::get($from, 'value');
        if ($from_value == '') {
            return Action::danger('เลือก วันที่เริ่มต้น ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }
        $to  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\ReceiptToDate');
        $to_value = Arr::get($to, 'value');
        if ($to_value == '') {
            return Action::danger('เลือก วันที่สิ้นสุด ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }

        return Action::openInNewTab('/receipt/report_r1/' . $branch_value . '/' . $from_value . '/' . $to_value);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            // Date::make('จากวันที่', 'from_date')->rules('required'),
            // Date::make('ถึงวันที่', 'to_date')->rules('required'),
        ];
    }
}
