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

class CreateBranchDeliveryItems extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'create-branch-delivery-items';
    }
    public function name()
    {
        return 'สร้างรายการจัดส่งสินค้าโดยรถสาขา';
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
        $delivery_no = IdGenerator::generate(['table' => 'deliveries', 'field' => 'delivery_no', 'length' => 15, 'prefix' => $branch->code  . date('ym')]);
        $select_orders = $models->filter(function ($item) {
            return data_get($item, 'order_status') == 'branch warehouse';
        });


        if ($select_orders->isNotEmpty()) {
            $delivery = Delivery::create([
                'delivery_no' => $delivery_no,
                'delivery_date' => $fields->delivery_date,
                'delivery_type' => 1,
                'branch_id' => $branch->id,
                'sender_id' => $fields->sender,
                'branch_route_id' => $fields->branch_route,
                'car_id' => $fields->car,
                'driver_id' => $fields->driver,
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

                    $branch_balance = Branch_balance::where('order_header_id', '=', $model->id)->first();
                    if (isset($branch_balance)) {
                        $branch_balance->delivery_id = $delivery->id;
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
            return Action::message('สร้างรายการจัดส่งโดยรถของสาขาเรียบร้อยแล้ว');
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
        $branch_routes = \App\Models\Branch_route::where('branch_id', auth()->user()->branch_id)->pluck('name', 'id');
        $branch_car = \App\Models\Car::all()->pluck('car_regist', 'id');
        $driver = \App\Models\Employee::where('branch_id', auth()->user()->branch_id)->get()->pluck('name', 'id');
        $sender = \App\Models\User::where('branch_id', auth()->user()->branch_id)->get()->pluck('name', 'id');
        return [
            Date::make('วันที่จัดส่ง', 'delivery_date')
                ->rules('required'),
            Select::make(__('Car regist'), 'car')
                ->rules('required')
                ->options($branch_car)
                ->displayUsingLabels()
                ->searchable(),
            Select::make(__('Driver'), 'driver')
                ->rules('required')
                ->options($driver)
                ->displayUsingLabels()
                ->searchable(),
            Select::make('พนักงานจัดส่ง', 'sender')
                ->options($sender)
                ->displayUsingLabels()
                ->searchable(),
            Select::make('เส้นทางขนส่งของสาขา', 'branch_route')
                ->options($branch_routes)
                ->displayUsingLabels(),
            Text::make('คำอธิบายรายการ/หมายเหตุ', 'description'),
        ];
    }
}
