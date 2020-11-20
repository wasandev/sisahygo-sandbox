<?php

namespace App\Nova\Actions;

use App\Models\Bankaccount;
use App\Models\Order_header;
use App\Models\Order_detail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

class OrderConfirmed extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'order_confirmed';
    }
    public function name()
    {
        return __('Order Confirmed');
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
            $hasitem = count($model->order_details);
            //$order_amount = $model->order_details->price->sum();

            if ($model->order_status <> 'new') {
                return Action::danger('ไม่สามารถยืนยันรายการที่ ยืนยัน/ยกเลิก ไปแล้วได้');
            } elseif ($hasitem) {

                $model->order_amount = $fields->order_amount;
                $model->paymenttype =  $fields->paymenttype;
                $model->bankaccount_id = $fields->bankaccount;
                $model->bankreference = $fields->refernce;
                $model->order_status = 'confirmed';

                $model->save();
                return Action::message('ยืนยันรายการเรียบร้อยแล้ว');
            }

            return Action::danger('ไม่สามารถยืนยันรายการได้ ->ไม่มีรายการสินค้า!');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $bankaccount = Bankaccount::all()->pluck('account_no', 'id');

        if ($this->model) {
            $order_amount = 0;
            $order_header = Order_header::find($this->model);
            $paymenttype = $order_header->paymenttype;

            $order_items = $order_header->order_details;
            foreach ($order_items as $order_item) {
                $sub_total = $order_item->price * $order_item->amount;
                $order_amount = $order_amount + $sub_total;
            }



            return [
                Currency::make('ค่าขนส่ง', 'order_amount')->default($order_amount)
                    ->readonly(),
                Select::make(__('Payment type'), 'paymenttype')->options([
                    'H' => 'เงินสดต้นทาง',
                    'T' => 'เงินโอนต้นทาง',
                    'E' => 'เก็บเงินปลายทาง',
                    'F' => 'วางบิลต้นทาง',
                    'L' => 'วางบิลปลายทาง'
                ])->displayUsingLabels()
                    ->default($paymenttype),
                NovaDependencyContainer::make([
                    Select::make(__('Account no'), 'bankaccount')
                        ->options($bankaccount)
                        ->displayUsingLabels(),
                    Text::make(__('Bank reference no'), 'reference'),
                ])->dependsOn('paymenttype', 'T'),
            ];
        }
        return [
            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->displayUsingLabels()
                ->default('H'),
            NovaDependencyContainer::make(
                [
                    Select::make(__('Account no'), 'bankaccount')
                        ->options($bankaccount)
                        ->displayUsingLabels(),
                    Text::make(__('Bank reference no'), 'reference'),
                ]
            )->dependsOn('paymenttype', 'T'),
        ];
    }
}
