<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PrintOrderBanktransfer extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-order-bank-transfer';
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


        $from  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\BankTransferDateFilter');
        $from_value = Arr::get($from, 'value');
        if ($from_value == '') {
            return Action::danger('เลือกวันที่ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }


        return Action::openInNewTab('/orderheader/report_t1/' . $from_value);
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
