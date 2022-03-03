<?php

namespace App\Nova\Actions;

use App\Models\Bankaccount;
use App\Models\Branch_balance;
use App\Models\Branchrec_order;
use App\Models\Delivery_detail;
use App\Models\Delivery_item;
use App\Models\Order_banktransfer;
use App\Models\Order_banktransfer_item;
use App\Models\Receipt;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Haruncpi\LaravelIdGenerator\IdGenerator;
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

            $model->discount_amount = $fields->discount_amount;
            $model->remark = $fields->description . '-' . $fields->discount_remark;

            if ($fields->tax_status) {
                $model->tax_amount = ($model->bal_amount - $fields->discount_amount) * 0.01;
                $model->pay_amount = ($model->bal_amount - $fields->discount_amount) - (($model->bal_amount - $fields->discount_amount) * 0.01);
            } else {
                $model->tax_amount = 0.00;
                $model->pay_amount = $model->bal_amount - $fields->discount_amount;
            }
            if ($fields->payment_by === 'T') {
                $model->payment_status = false;
            } else {
                $model->payment_status = true;
            }


            $model->branchpay_date = $fields->paydate;
            $model->save();

            if ($branch_order->order_status == 'completed' || $branch_order->order_status == 'problem') {
                $delivery_item = Delivery_item::find($delivery_detail->delivery_item_id);

                $branch_order->branchpay_by =  $fields->payment_by;



                if ($fields->payment_by == 'T') {
                    $model->payment_status = false;
                    $branch_order->branchpay_by = 'T';
                    $branch_order->bankaccount_id = $fields->bankaccount;
                    $branch_order->bankreference = $fields->refernce;
                    $branch_order->payment_status = false;
                    $delivery_detail->payment_status = false;
                    $delivery_item->branchpay_by = 'T';
                    $delivery_item->bankaccount_id = $fields->bankaccount;
                    $delivery_item->bankreference = $fields->reference;
                    $delivery_item->discount_amount = $fields->discount_amount;
                    if ($fields->tax_status) {
                        $delivery_item->tax_amount = ($delivery_item->payment_amount - $fields->discount_amount)  * 0.01;
                        $delivery_item->pay_amount = ($delivery_item->payment_amount - $fields->discount_amount) - (($delivery_item->payment_amount - $fields->discount_amount)  * 0.01);
                    } else {
                        $delivery_item->tax_amount = 0.00;
                        $delivery_item->pay_amount = $delivery_item->payment_amount - $fields->discount_amount;
                    }
                    //Create Bank_transfer

                    $order_banktransfer = Order_banktransfer::create([
                        'order_header_id' => $model->order_header_id,
                        'transfer_date' => $fields->paydate,
                        'customer_id' => $model->customer_id,
                        'branch_id' => $model->branch_id,
                        'status' => false,
                        'transfer_type' => 'E',
                        'transfer_amount' => $model->pay_amount,
                        'tax_amount' => $model->tax_amount,
                        'discount_amount' => $model->discount_amount,
                        'bankaccount_id' => $fields->bankaccount,
                        'reference' => $fields->bankreference,
                        'user_id' => auth()->user()->id
                    ]);
                    Order_banktransfer_item::create([
                        'order_banktransfer_id' => $order_banktransfer->id,
                        'order_header_id' => $model->order_header_id,
                        'user_id' => auth()->user()->id,
                    ]);
                } elseif ($fields->payment_by == 'C') {

                    $model->payment_status = true;
                    $branch_order->branchpay_by = 'C';
                    $branch_order->payment_status = true;
                    $delivery_detail->payment_status = true;
                    $delivery_item->branchpay_by = 'C';
                    $receipt_no = IdGenerator::generate(['table' => 'receipts', 'field' => 'receipt_no', 'length' => 15, 'prefix' => 'RC' . date('Ymd')]);
                    $receipt = Receipt::create([
                        'receipt_no' => $receipt_no,
                        'receipt_date' => $model->branchpay_date,
                        'branch_id' => auth()->user()->branch_id,
                        'customer_id' => $model->customer_id,
                        'total_amount' => $model->bal_amount,
                        'discount_amount' => $model->discount_amount,
                        'tax_amount' => $model->tax_amount,
                        'pay_amount' => $model->pay_amount,
                        'receipttype' => 'E',
                        'branchpay_by' => 'C',
                        'description' => $model->remark,
                        'user_id' => auth()->user()->id,
                    ]);
                    $model->receipt_id = $receipt->id;
                    $model->save();
                }

                $branch_order->save();
                $delivery_item->save();
                $delivery_detail->save();

                return Action::push('/resources/branch_balances/');
            } else {
                return Action::danger('รายการนี้ยังไม่ได้ทำรายการจัดส่งหรือลูกยังไม่ได้รับสินค้า โปรดตรวจสอบ');
            }
        }


        //
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
                    ->rules('required'),

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
                Boolean::make('มีส่วนลด', 'discount_flag'),
                NovaDependencyContainer::make([
                    Currency::make('ส่วนลด', 'discount_amount'),
                    Text::make('สาเหตุการลด', 'discount_remark')->rules('required'),
                ])->dependsOn('discount_flag', true),
                Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),
                Text::make('หมายเหตุเพิ่มเติม', 'remark')
            ];
        }
        return [
            Currency::make('ค่าขนส่งที่ต้องจัดเก็บ', 'bal_amount')
                ->readonly(),
            Date::make('วันที่รับชำระ', 'paydate')
                ->default(today()->toDateString())
                ->rules('required'),
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

            Boolean::make('มีส่วนลด', 'discount_flag'),
            NovaDependencyContainer::make([
                Currency::make('ส่วนลด', 'discount_amount'),
                Text::make('สาเหตุการลด', 'discount_remark')->rules('required'),
            ])->dependsOn('discount_flag', true),
            Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),
        ];
    }
}
