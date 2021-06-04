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
        $decodedFilters = collect(json_decode(base64_decode($this->filter), true));

        $paymenttype  = $decodedFilters->firstWhere('class', 'App\Nova\Filters\Lenses\LensesPaymentType');

        $type_value = Arr::get($paymenttype, 'value.typeb');

        if ($type_value) {
            $type_str = 'B';
        } else {
            $type_str = 'all';
        }

        $from  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\Lenses\PaymentLensFromDate');
        $from_value = Arr::get($from, 'value');
        if ($from_value == '') {
            return Action::danger('เลือก วันที่เริ่มต้น ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }
        $to  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\Lenses\PaymentLensToDate');
        $to_value = Arr::get($to, 'value');
        if ($to_value == '') {
            return Action::danger('เลือก วันที่สิ้นสุด ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }
        return Action::openInNewTab('/car/report_11/' . $from_value . '/' . $to_value . '/' . $type_str);
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
