<?php

namespace App\Nova\Actions;

use App\Models\Carpayment;
use App\Models\Incometype;
use App\Models\Vendor;
use App\Models\Withholdingtax;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PostWhTax extends Action
{
    use InteractsWithQueue, Queueable;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function uriKey()
    {
        return 'post-wh-tax';
    }
    public function name()
    {
        return 'ยืนยันรายการภาษีหัก ณ ที่จ่ายรถ';
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


        $to  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CarpaymentToDate');

        $to_value = Arr::get($to, 'value');

        if ($to_value == '') {
            return Action::danger('เลือก วันที่ที่ต้องการที่เมนูกรองข้อมูลก่อน');
        }

        foreach ($models as $model) {
            //get payment_amount,tax_amount
            $car_pay = Carpayment::where('vendor_id', $model->id)
                ->whereMonth('payment_date', date('m', strtotime($to_value)))
                ->whereYear('payment_date', date('Y', strtotime($to_value)))
                ->where('carpayments.status', '=', true)
                ->where('carpayments.tax_flag', '=', true)
                ->sum('amount');
            $car_tax = Carpayment::where('vendor_id', $model->id)
                ->whereMonth('payment_date', date('m', strtotime($to_value)))
                ->whereYear('payment_date', date('Y', strtotime($to_value)))
                ->where('carpayments.status', '=', true)
                ->where('carpayments.tax_flag', '=', true)
                ->sum('tax_amount');

            $vendor = Vendor::find($model->id);
            if ($vendor->type == 'company') {
                $payertype = '2';
            } else {
                $payertype = '1';
            }
            $incometype = Incometype::where('code', '020')
                ->where('payertype', $payertype)->first();

            $whtax = Withholdingtax::where('vendor_id', $model->id)
                ->whereMonth('pay_date', date('m', strtotime($to_value)))
                ->whereYear('pay_date', date('Y', strtotime($to_value)))
                ->first();


            if (isset($whtax)) {
                //update
                $whtax->pay_amount =  $car_pay;
                $whtax->tax_amount =  $car_tax;
                $whtax->payertype = $payertype;
                $whtax->incometype_id = $incometype->id;
                $whtax->save();
            } else {
                //create
                $newwhtax = new Withholdingtax();
                $newwhtax->pay_date = date("Y-m-d", strtotime($to_value));
                $newwhtax->payertype = $payertype;
                $newwhtax->vendor_id = $model->id;
                $newwhtax->incometype_id = $incometype->id;
                $newwhtax->pay_amount = $car_pay;
                $newwhtax->tax_amount = $car_tax;
                $newwhtax->user_id = auth()->user()->id;
                $newwhtax->description = 'ค่าขนส่ง';
                $newwhtax->save();
            }
        }
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
