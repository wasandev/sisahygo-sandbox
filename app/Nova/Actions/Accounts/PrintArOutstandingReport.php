<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;

use function PHPUnit\Framework\isNull;

class PrintArOutstandingReport extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-ar-outstanding-report';
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
        $customer_value = $fields->ar_customer;
        $branch_value = $fields->ar_branch;
        if ($branch_value == '') {
            $branch_value = 'all';
        }

        $to_value = $fields->to;
        if ($customer_value == '') {
            $customer_value = 'all';
        }
        if ($to_value == '') {
            return Action::danger('เลือกวันที่ ที่ต้องการก่อน');
        }

        return Action::openInNewTab('/ar/report_17/' . $branch_value . '/' . $customer_value . '/'  . $to_value);
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $customers = \App\Models\Ar_customer::whereHas('ar_balances')->pluck('name', 'id');
        $branchs = \App\Models\Branch::pluck('name', 'id');
        return [
            Select::make('เลือกสาขา', 'ar_branch')
                ->options($branchs)
                ->searchable()
                ->help('เลือกสาขาที่ต้องการออกรายงาน หากต้องการออกรายงานทั้งหมดไม่ต้องเลือก'),
            Select::make('เลือกลูกค้า', 'ar_customer')
                ->options($customers)
                ->searchable()
                ->help('เลือกชื่อลูกค้าที่ต้องการออกรายงาน ถ้าต้องการออกรายงานทั้งหมดไม่ต้องเลือก'),

            Date::make('วันที่สิ้นสุด', 'to')
                ->rules('required')

        ];
    }
}
