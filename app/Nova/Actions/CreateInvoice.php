<?php

namespace App\Nova\Actions;

use App\Models\Invoice;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;

class CreateInvoice extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'ceate_invoice';
    }
    public function name()
    {
        return 'สร้างใบแจ้งหนี้';
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
        foreach ($models as $model) {
            // if ($model->invoice_id) {
            //     return Action::danger('รายการนี้สร้างใบแจ้งหนี้แล้ว');
            // }
            $select_orders = $models->filter(function ($item) {
                return data_get($item, 'invoice_id') == null;
            });

            if ($select_orders->isNotEmpty()) {

                $cust_groups = $select_orders->groupBy('customer_id')->all();
                $invoice_custs = $cust_groups;

                foreach ($invoice_custs as $cust => $cust_groups) {
                    $invoice_no = IdGenerator::generate(['table' => 'invoices', 'field' => 'invoice_no', 'length' => 15, 'prefix' => 'D'  . date('Ymd')]);

                    $invoice = Invoice::create([
                        'invoice_no' => $invoice_no,
                        'invoice_date' => today(),
                        'customer_id' => $cust,
                        'description' => $fields->description,
                        'user_id' => auth()->user()->id,

                    ]);
                    foreach ($cust_groups as $model) {
                        $model->invoice_id = $invoice->id;
                        $model->save();
                    }
                }
            } else {
                return Action::danger('ไม่มีรายการสร้างใบแจ้งหนี้หรือรายการที่เลือกสร้างใบแจ้งหนี้แล้ว');
            }
        }
        return Action::message('สร้างใบแจ้งหนี้เรียบร้อยแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('หมายเหตุเพิ่มเติมในท้ายใบแจ้งหนี้', 'description')
        ];
    }
}
