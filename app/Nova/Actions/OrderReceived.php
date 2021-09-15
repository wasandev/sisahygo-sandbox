<?php

namespace App\Nova\Actions;

use App\Models\Bankaccount;
use App\Models\Branchrec_order;
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
use Laravel\Nova\Fields\Boolean;

class OrderReceived extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'order-received';
    }
    public function name()
    {
        return 'ลูกค้ารับสินค้าที่สาขา';
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

            if ($model->order_status <> 'branch warehouse') {
                return Action::danger('ไม่สามารถยืนยันการรับสินค้ารายการนี้ได้');
            }

            $model->order_status = 'completed';

            $model->branchpay_by =  $fields->payment_by;

            if ($model->paymenttype == 'E') {

                if ($fields->payment_by == 'T') {
                    $model->bankaccount_id = $fields->bankaccount;
                    $model->bankreference = $fields->refernce;
                    $model->payment_status = false;
                } elseif ($fields->payment_by == 'C') {


                    $model->payment_status = true;
                }
            }

            $model->save();


            if ($model->payment_amount > 0) {

                $model->discount_amount = $fields->discount_amount;
                $model->branchpay_by = $fields->payment_by;
                if ($fields->tax_status) {
                    $model->tax_amount = $model->payment_amount * 0.01;
                    $model->pay_amount = $model->payment_amount - $fields->discount_amount - ($model->payment_amount * 0.01);
                } else {
                    $model->tax_amount = 0.00;
                    $model->pay_amount = $model->payment_amount - $fields->discount_amount;
                }
                if ($fields->payment_by === 'T') {
                    $model->payment_status = false;
                    $model->bankaccount_id = $fields->bankaccount;
                    $model->bankreference = $fields->reference;
                } else {
                    $model->payment_status = true;
                }
            } else {
                $model->payment_status = false;
            }
            $model->description = $fields->description;
            $model->delivery_status = 1;
            $model->save();
        }

        return Action::message('ทำรายการเรียบร้อยแล้ว');
    }


    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        if (auth()->user()->branch->type == 'partner') {
            $bankaccount = Bankaccount::where('defaultflag', '=', true)
                ->where('branch_id', auth()->user()->branch_id)
                ->pluck('account_no', 'id');
        } else {
            $bankaccount = Bankaccount::where('defaultflag', '=', true)
                ->pluck('account_no', 'id');
        }

        if ($this->model) {
            $ฺbranchrec_order = Branchrec_order::find($this->model);

            if ($ฺbranchrec_order->order_amount > 0) {

                return [
                    Currency::make('ค่าขนส่งที่ต้องจัดเก็บ', 'payment_amount')
                        ->default($ฺbranchrec_order->order_amount)
                        ->readonly(),
                    Select::make('รับชำระด้วย', 'payment_by')->options([
                        'C' => 'เงินสด',
                        'T' => 'เงินโอน',
                    ])->displayUsingLabels()
                        ->default('C'),
                    NovaDependencyContainer::make([
                        Select::make(__('Account no'), 'bankaccount')
                            ->options($bankaccount)
                            ->displayUsingLabels()
                            ->rules('required'),
                        Text::make(__('Bank reference no'), 'reference'),
                    ])->dependsOn('payment_by', 'T'),
                    Currency::make('ส่วนลด', 'discount_amount'),
                    Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),

                    Text::make('หมายเหตุเพิ่มเติม', 'description')
                ];
            } else {
                return [];
            }
        }
        return [
            Currency::make('ค่าขนส่งที่ต้องเก็บ', 'payment_amount')
                ->readonly(),

            Select::make('รับชำระด้วย', 'payment_by')->options([
                'C' => 'เงินสด',
                'T' => 'เงินโอน',
            ])->displayUsingLabels()
                ->default('C'),
            NovaDependencyContainer::make([
                Select::make(__('Bank Account no'), 'bankaccount')
                    ->options($bankaccount)
                    ->displayUsingLabels()
                    ->rules('required'),
                Text::make(__('Bank reference no'), 'reference'),
            ])->dependsOn('payment_by', 'T'),
            Currency::make('ส่วนลด', 'discount_amount'),
            Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),
        ];
    }
}
