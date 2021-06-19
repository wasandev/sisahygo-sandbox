<?php

namespace App\Nova\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

use function PHPUnit\Framework\isNull;

class PrintCarCardReportByDay extends Action
{
    use InteractsWithQueue, Queueable;
    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-car-card-report-by-day';
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
        if ($fields->cardtype == 'car') {
            $car  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CarbalanceByCar');
            $car_value = Arr::get($car, 'value');
            if ($car_value == '') {
                return Action::danger('เลือก ทะเบียนรถ ที่ต้องการที่เมนูกรองข้อมูลก่อน');
            }
        } else {
            $owner  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CarbalanceByOwner');
            $owner_value = Arr::get($owner, 'value');
            if ($owner_value == '') {
                return Action::danger('เลือก เจ้าของรถ ที่ต้องการที่เมนูกรองข้อมูลก่อน');
            }
        }

        $from  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CarbalanceFromDate');
        $from_value = Arr::get($from, 'value');
        if ($from_value == '') {
            return Action::danger('เลือก วันที่เริ่มต้น ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }
        $to  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CarbalanceToDate');
        $to_value = Arr::get($to, 'value');
        if ($to_value == '') {
            return Action::danger('เลือก วันที่สิ้นสุด ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }

        if ($fields->cardtype == 'car') {
            return Action::openInNewTab('/car/report_13/' . $car_value . '/' . $from_value . '/' . $to_value);
        } else {
            return Action::openInNewTab('/car/report_14/' . $owner_value . '/' . $from_value . '/' . $to_value);
        }
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('เลือกประเภท', 'cardtype')
                ->options([
                    'car' => 'ทะเบียนคุมตามทะเบียนรถ',
                    'owner' => 'ทะเบียนคุมตามเจ้าของรถ'
                ])->displayUsingLabels()
                ->rules('required')
        ];
    }
}
