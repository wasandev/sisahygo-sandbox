<?php

namespace App\Nova\Actions;

use App\Models\Bankaccount;
use App\Models\Branch_balance;
use App\Models\Branchrec_order;
use App\Models\Delivery_detail;
use App\Models\Delivery_item;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class BranchReceipt extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'branch-receipt';
    }
    public function name()
    {
        return 'รับชำระเงิน';
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

            if ($model->payment_status) {
                return Action::danger('รายการนี้ชำระเงินไปแล้ว');
            }

            $branch_order = Branchrec_order::find($model->order_header_id);
            $delivery_detail = Delivery_detail::where('order_header_id', $model->order_header_id)->first();
            if (isset($delivery_detail)) {
                $delivery_item = Delivery_item::find($delivery_detail->delivery_item_id);

                $branch_order->branchpay_by =  $fields->payment_by;

                if ($fields->payment_by === 'T') {
                    $model->payment_status = false;
                    $branch_order->branchpay_by = 'T';
                    $branch_order->bankaccount_id = $fields->bankaccount;
                    $branch_order->bankreference = $fields->refernce;
                    $branch_order->payment_status = false;
                    $delivery_detail->payment_status = false;
                    $delivery_item->branchpay_by = 'T';
                    $delivery_item->bankaccount_id = $fields->bankaccount;
                    $delivery_item->bankreference = $fields->reference;
                    $delivery_item->payment_status = false;
                } elseif ($fields->payment_by === 'C') {
                    $model->payment_status = true;
                    $branch_order->branchpay_by = 'C';
                    $branch_order->payment_status = true;
                    $delivery_detail->payment_status = true;
                    $delivery_item->branchpay_by = 'C';
                    $delivery_item->payment_status = true;
                }

                $branch_order->save();
                $delivery_detail->save();
                $delivery_item->save();
            } else {
                return Action::danger('รายการนี้ยังไม่ได้ทำรายการจัดส่ง โปรดตรวจสอบ');
            }


            $model->discount_amount = $fields->discount_amount;

            if ($fields->tax_status) {
                $model->tax_amount = $model->bal_amount * 0.01;
                $model->pay_amount = $model->bal_amount - $fields->discount_amount - ($model->bal_amount * 0.01);
            } else {
                $model->tax_amount = 0.00;
                $model->pay_amount = $model->bal_amount - $fields->discount_amount;
            }
            if ($fields->payment_by === 'T') {
                $model->payment_status = false;
            } else {
                $model->payment_status = true;
            }

            $model->remark = $fields->description;
            $model->branchpay_date = $fields->paydate;
            $model->save();
        }


        return Action::push('/resources/branch_balances/');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $bankaccount = Bankaccount::where('defaultflag', '=', true)->pluck('account_no', 'id');

        if ($this->model) {
            $branch_balance = Branch_balance::find($this->model);


            return [
                Currency::make('ค่าขนส่งที่ต้องจัดเก็บ', 'bal_amount')->default($branch_balance->bal_amount)
                    ->readonly(),
                Date::make('วันที่รับชำระ', 'paydate')
                    ->default(today()->toDateString()),

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

                Text::make('หมายเหตุเพิ่มเติม', 'remark')
            ];
        }
        return [
            Currency::make('ค่าขนส่งที่ต้องจัดเก็บ', 'bal_amount')
                ->readonly(),
            Date::make('วันที่รับชำระ', 'paydate')
                ->default(today()->toDateString()),
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
