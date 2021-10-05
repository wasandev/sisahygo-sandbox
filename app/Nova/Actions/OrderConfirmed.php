<?php

namespace App\Nova\Actions;

use App\Models\Bankaccount;
use App\Models\Order_header;
use App\Models\Order_detail;
use App\Models\Routeto_branch;
use App\Models\User;
use App\Models\Waybill;
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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;


class OrderConfirmed extends Action
{
    use InteractsWithQueue, Queueable;

    public $withoutActionEvents = true;
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
            if ($hasitem) {

                if ($fields->paymenttype == 'F'  && $model->customer->paymenttype <> 'Y') {
                    return Action::danger('ลูกค้ารายนี้ไม่ใช่ลูกหนี้การค้า');
                }
                if ($fields->paymenttype == 'L'  && $model->to_customer->paymenttype <> 'Y') {
                    return Action::danger('ลูกค้ารายนี้ไม่ใช่ลูกหนี้การค้า');
                }
                if ($model->order_status <> 'new') {
                    return Action::danger('ไม่สามารถยืนยันรายการที่ ยืนยัน/ยกเลิก ไปแล้วได้');
                }

                $model->order_amount = $fields->order_amount;
                $model->paymenttype =  $fields->paymenttype;
                $model->bankaccount_id = $fields->bankaccount;
                $model->bankreference = $fields->refernce;
                $model->waybill_id = $fields->waybill_branch;
                $model->loader_id = $fields->loader;

                $model->order_status = 'confirmed';

                if (isset($fields->ordercancel)) {
                    $model->ordercancel_id = $fields->cancelorder;
                }

                $model->save();


                return Action::push('/resources/order_headers/');
            } else {
                return Action::danger('ไม่สามารถยืนยันรายการได้ ->ไม่มีรายการสินค้า!');
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
        $bankaccount = Bankaccount::where('defaultflag', '=', true)->pluck('account_no', 'id');
        $waybills = Waybill::where('waybill_status', 'loading')->pluck('waybill_no', 'id');
        $loaders = User::where('branch_id', '=', auth()->user()->branch_id);
        $ordercancels = Order_header::where('order_status', 'cancel')
            ->whereYear('order_header_date', date('Y'))
            ->whereMonth('order_header_date', date('m'))
            ->pluck('order_header_no', 'id');
        $waybillOptions = [];

        if ($this->model) {
            $order_amount = 0;
            $order_header = Order_header::find($this->model);
            $paymenttype = $order_header->paymenttype;

            $order_items = $order_header->order_details;
            foreach ($order_items as $order_item) {
                $sub_total = $order_item->price * $order_item->amount;
                $order_amount = $order_amount + $sub_total;
            }

            $routeto_branch = Routeto_branch::where('dest_branch_id',  $order_header->branch_rec_id)->first();
            if (isset($routeto_branch)) {
                $waybillbranches = Waybill::with('car')
                    ->where('routeto_branch_id', '=', $routeto_branch->id)
                    ->where('waybill_status', '=', 'loading')
                    ->get();
            }
            if (isset($waybillbranches)) {
                foreach ($waybillbranches as $waybill) {
                    $waybillOptions[] = [
                        ['branchwaybill' => ['id' => $waybill->id, 'name' => $waybill->waybill_no . '-' . $waybill->car->car_regist]],
                    ];
                }
                $selectOptions = collect($waybillOptions)->flatten(1);

                $waybillOptions = $selectOptions->pluck('branchwaybill.name', 'branchwaybill.id');
            }
            if (isset($waybillOptions)) {

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
                        Select::make(__('Bank Account no'), 'bankaccount')
                            ->options($bankaccount)
                            ->displayUsingLabels(),
                        Text::make(__('Bank reference no'), 'reference'),
                    ])->dependsOn('paymenttype', 'T'),
                    Select::make('สินค้าขึ้นรถแล้ว เลือกใบกำกับ', 'waybill_branch')
                        ->options($waybillOptions)
                        ->displayUsingLabels()
                        ->searchable(),
                    Select::make('พนักงานจัดขึ้น', 'loader')
                        ->options($loaders)
                        ->displayUsingLabels()
                        ->searchable(),
                    Select::make('ออกแทนบิลยกเลิกเลขที่', 'ordercancel')
                        ->options($ordercancels)
                        ->displayUsingLabels()
                        ->searchable(),
                ];
            }
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
                    Select::make(__('Bank Account no'), 'bankaccount')
                        ->options($bankaccount)
                        ->displayUsingLabels(),
                    Text::make(__('Bank reference no'), 'reference'),
                ]
            )->dependsOn('paymenttype', 'T'),
            Select::make(__('Waybill'), 'waybill_branch')
                ->options($waybills)
                ->displayUsingLabels()
                ->searchable(),
            Select::make('พนักงานจัดขึ้น', 'loader')
                ->options($loaders)
                ->displayUsingLabels()
                ->searchable(),
            Select::make('ออกแทนบิลยกเลิกเลขที่', 'ordercancel')
                ->options($ordercancels)
                ->displayUsingLabels()
                ->searchable(),
        ];
    }
}
