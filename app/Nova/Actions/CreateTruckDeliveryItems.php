<?php

namespace App\Nova\Actions;


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

class CreateTruckDeliveryItems extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'create_delivery_items';
    }
    public function name()
    {
        return 'สร้างรายการจัดส่งสินค้าโดยรถบรรทุก';
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

        $waybill = \App\Models\Branchrec_waybill::where('id', '=', $models->first()->waybill_id)->first();
        $branch = \App\Models\Branch::find(auth()->user()->branch_id);
        $waybill_order = \App\Models\Branchrec_order::where('waybill_id', $waybill->id)->get();


        $branch_warehouses =  $waybill_order->diff($models);

        $delivery_no = IdGenerator::generate(['table' => 'deliveries', 'field' => 'delivery_no', 'length' => 15, 'prefix' => $branch->code  . date('Ymd')]);

        $select_orders = $models->filter(function ($item) {
            return data_get($item, 'order_status') == 'arrival';
        });
        //update other order status to  branchwarehouse
        $branch_orders = $branch_warehouses->filter(function ($item) {
            return data_get($item, 'order_status') == 'arrival';
        });

        if ($select_orders->isNotEmpty() || $branch_orders->isNotEmpty()) {

            $delivery = Delivery::create([
                'delivery_no' => $delivery_no,
                'delivery_date' => $fields->delivery_date,
                'waybill_id' => $waybill->id,
                'delivery_type' => 0,
                'branch_id' => $branch->id,
                //'receipt_amount' => $branchrec_amount,
                'branch_route_id' => $fields->branch_route,
                'car_id' => $waybill->car_id,
                'driver_id' => $waybill->driver_id,
                'user_id' => auth()->user()->id,
                'decription' => $fields->description,
            ]);
            $cust_groups = $select_orders->groupBy('customer_rec_id')->all();
            $bal_custs = $cust_groups;

            foreach ($bal_custs as $cust => $cust_groups) {

                $delivery_item = Delivery_item::create([
                    'delivery_id' => $delivery->id,
                    'customer_id' => $cust,
                    'delivery_status' => false,
                    'payment_status' => false,
                    'user_id' => auth()->user()->id,

                ]);

                foreach ($cust_groups as $model) {

                    $model->order_status = 'delivery';
                    $model->save();

                    Delivery_detail::create([
                        'delivery_item_id' =>  $delivery_item->id,
                        'order_header_id' => $model->id,
                        'delivery_status' => false,
                        'payment_status' => false,
                    ]);

                    Order_status::create([
                        'order_header_id' => $model->id,
                        'status' => 'delivery',
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }

            foreach ($branch_orders as $branch_warehouse_order) {

                $branch_warehouse_order->order_status = 'branch warehouse';
                $branch_warehouse_order->save();
                $order_status = Order_status::create([
                    'order_header_id' => $branch_warehouse_order->id,
                    'status' => 'branch warehouse',
                    'user_id' => auth()->user()->id,
                ]);
            }
            return Action::message('สร้างรายการจัดส่งโดยรถบรรทุก และ/หรือ สร้างรายการใบรับส่งที่ลงไว้สาขาแล้ว');
        }


        return Action::danger('รายการใบรับส่งที่เลือก ไม่สามารถทำรายการได้ เลือกได้เฉพาะสถานะ -arrival- เท่านั้น');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $branch_routes = \App\Models\Branch_route::where('branch_id', auth()->user()->branch_id)->pluck('name', 'id');

        return [
            Date::make('วันที่จัดส่ง', 'delivery_date')
                ->rules('required'),
            Select::make('เส้นทางขนส่งของสาขา', 'branch_route')
                ->options($branch_routes)
                ->displayUsingLabels()
                ->rules('required'),
            Text::make('คำอธิบายรายการ/หมายเหตุ', 'description'),
        ];
    }
}
