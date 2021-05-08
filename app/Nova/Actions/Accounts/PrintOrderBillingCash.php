<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

use function PHPUnit\Framework\isNull;

class PrintOrderBillingCash extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-order-billing-cash';
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
        $branch = $decodedFilters->firstWhere('class', 'App\Nova\Filters\LensBranchFilter');

        $branch_value = Arr::get($branch, 'value');

        if ($branch_value == '') {
            return Action::message('เลือกสาขาที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }

        $orderdate  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\OrderDateFilter');
        $orderdate_value = Arr::get($orderdate, 'value');
        if ($orderdate_value == '') {
            return Action::message('เลือกวันที่ ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }
        return Action::openInNewTab('/orderheader/report_1/' . $branch_value . '/' . $orderdate_value);
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
