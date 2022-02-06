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

class PrintArCardReport extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-ar-card-report';
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
        $from_value = $fields->from;
        $to_value = $fields->to;

        return Action::openInNewTab('/ar/report_16/' . $customer_value . '/' . $from_value . '/' . $to_value);
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $customers = \App\Models\Ar_customer::whereHas('ar_balances')->pluck('name', 'id');
        return [
            Select::make('เลือกลูกค้า', 'ar_customer')
                ->options($customers)
                ->searchable()
                ->rules('required'),
            Date::make('วันที่เริ่มต้น', 'from')
                ->rules('required'),
            Date::make('วันที่สิ้นสุด', 'to')
                ->rules('required')

        ];
    }
}
