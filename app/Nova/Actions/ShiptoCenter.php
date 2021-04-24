<?php

namespace App\Nova\Actions;

use App\Models\Dropship_tran;
use App\Models\Order_status;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;

class ShiptoCenter extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'shipto-center';
    }
    public function name()
    {
        return 'สร้างใบจัดส่งสินค้าจากตัวแทน';
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

        $dropship_tran_no = IdGenerator::generate(['table' => 'dropship_trans', 'field' => 'dropship_tran_no', 'length' => 15, 'prefix' => $branch->code  . date('Ymd')]);
        $select_orders = $models->filter(function ($item) {
            return data_get($item, 'shipto_center') == '0';
        });
        if ($select_orders->isNotEmpty()) {
            $dropship_tran = Dropship_tran::create([
                'dropship_tran_no' => $dropship_tran_no,
                'dropship_tran_date' => $fields->dropship_tran_date,
                'branch_id' => $branch->id,
                'employee_id' => $fields->employee,
                'tran_amount' => $select_orders->sum('order_amount'),
                'dropship_income' => $select_orders->sum('order_amount') * $branch->dropship_rate / 100,
                'scash_amount' => $select_orders->where('paymenttype', '=', 'H')->sum('order_amount'),
                'dcash_amount' => $select_orders->where('paymenttype', '=', 'E')->sum('order_amount'),
                'user_id' => auth()->user()->id,
            ]);

            foreach ($select_orders as $model) {
                $model->dropship_tran_id = $dropship_tran->id;
                $model->shipto_center = '1';
                $model->save();

                Order_status::create([
                    'order_header_id' => $model->id,
                    'status' => 'in transit to center',
                    'user_id' => auth()->user()->id,
                ]);
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
        $sender = \App\Models\Employee::where('branch_id', auth()->user()->branch_id)->get()->pluck('name', 'id');
        return [
            Date::make('วันที่จัดส่ง', 'dropship_tran_date')
                ->rules('required'),

            Select::make('พนักงานจัดส่ง', 'employee')
                ->options($sender)
                ->displayUsingLabels(),
        ];
    }
}
