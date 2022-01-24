<?php

namespace App\Nova\Actions;

use App\Models\Branch_balance;
use App\Models\Delivery;
use App\Models\Delivery_detail;
use App\Models\Delivery_item;
use App\Models\Order_status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AddOrderToDelivery extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'add-order-to-delivery';
    }
    public function name()
    {
        return 'เพิ่มใบรับส่ง เข้าใบจัดส่งสินค้า';
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

        $branch = \App\Models\Branch::find(auth()->user()->branch_id);
        $select_orders = $models->filter(function ($item) {
            return data_get($item, 'order_status') == 'branch warehouse';
        });


        if ($select_orders->isNotEmpty()) {

            $cust_groups = $select_orders->groupBy('customer_rec_id')->all();
            $bal_custs = $cust_groups;

            foreach ($bal_custs as $cust => $cust_groups) {
                $delivery_item = Delivery_item::create([
                    'delivery_id' => $fields->branch_delivery,
                    'customer_id' => $cust,
                    'delivery_status' => false,
                    'payment_status' => false,
                    'user_id' => auth()->user()->id,

                ]);

                foreach ($cust_groups as $model) {

                    $model->order_status = 'delivery';
                    $model->save();

                    $branch_balance = Branch_balance::where('order_header_id', '=', $model->id)->first();
                    if (isset($branch_balance)) {
                        $branch_balance->delivery_id = $fields->branch_delivery;
                        $branch_balance->save();
                    }
                    if ($model->paymenttype == 'H') {
                        $payment_status = true;
                    } else {
                        $payment_status = false;
                    }
                    Delivery_detail::create([
                        'delivery_item_id' =>  $delivery_item->id,
                        'order_header_id' => $model->id,
                        'delivery_status' => false,
                        'payment_status' => $payment_status,
                    ]);

                    Order_status::create([
                        'order_header_id' => $model->id,
                        'status' => 'delivery',
                        'user_id' => auth()->user()->id,
                    ]);
                }
                $delivery_detail_notpay = Delivery_detail::where('delivery_item_id', $delivery_item->id)
                    ->where('payment_status', '=', false)
                    ->count();
                if ($delivery_detail_notpay == 0) {
                    $delivery_item->payment_status = true;
                    $delivery_item->save();
                }
            }
            return Action::message('เพิ่มรายการใบรับส่งเข้าใบจัดส่งที่ต้องการเรียบร้อยแล้ว');
        }
        return Action::danger('ทำรายการจัดส่งได้กับใบรับส่งที่มีสถานะเป็น branch warehouse - สินค้าอยู่คลังสาขา รอการจัดส่ง เท่านั้น ');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $deliveries = Delivery::with('car')
            ->where('completed', '=', false)
            ->where('branch_id', '=', auth()->user()->branch_id)
            ->get();
        $deliveryOptions = [];

        foreach ($deliveries as $delivery) {
            $deliveryOptions[] = [
                ['deliveries' => ['id' => $delivery->id, 'name' => $delivery->car->car_regist . '-' . $delivery->delivery_no . '-'  .  $delivery->branch_route->name]],
            ];
        }
        $selectOptions = collect($deliveryOptions)->flatten(1);

        $deliveryOptions = $selectOptions->pluck('deliveries.name', 'deliveries.id');




        if (isset($deliveryOptions)) {
            return [

                Select::make('ใบจัดส่ง', 'branch_delivery')
                    ->options($deliveryOptions)
                    ->displayUsingLabels()
                    ->rules('required')

            ];
        }
    }
}
