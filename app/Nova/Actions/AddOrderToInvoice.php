<?php

namespace App\Nova\Actions;

use App\Models\Ar_balance;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class AddOrderToInvoice extends Action
{
    use InteractsWithQueue, Queueable;

    protected $model;
    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'add_order_to_invoice';
    }
    public function name()
    {
        return 'นำใบรับส่งเข้าใบแจ้งหนี้';
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
            $model->invoice_id = $fields->invoice;
            $model->save();
        }
        return Action::message('เพิ่มใบรับส่งเข้าใบแจ้งหนี้เรียบร้อยแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {

        if ($this->model) {
            $ar_balance = Ar_balance::find($this->model);
            $invoices = Invoice::where('customer_id', $ar_balance->customer_id)
                ->where('status', '=', 'new')->pluck('invoice_no', 'id');

            return [
                Select::make('เลือกใบแจ้งหนี้', 'invoice')
                    ->options($invoices)
                    ->searchable()
            ];
        }
        $invoices = Invoice::all()->pluck('invoice_no', 'id');
        return [
            Select::make('เลือกใบแจ้งหนี้', 'invoice')
                ->options($invoices)
                ->searchable()
        ];
    }
}
