<?php

namespace App\Nova\Actions;

use App\Models\Bankaccount;
use App\Models\Branch_balance;
use App\Models\Branchrec_order;
use App\Models\Order_banktransfer;
use App\Models\Order_status;
use App\Models\Receipt;
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
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Laravel\Nova\Fields\Boolean;

class OrderReceived extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'order-received';
    }
    public function name()
    {
        return 'ยืนยันรายการ ลูกค้ารับสินค้าที่สาขา';
    }
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
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

        $select_orders = $models->filter(function ($item) {
            return data_get($item, 'order_status') == 'branch warehouse';
        });

        if ($select_orders->isNotEmpty()) {

            $cust_groups = $select_orders->groupBy('customer_rec_id')->all();
            $rec_custs = $cust_groups;
            foreach ($rec_custs as $cust => $cust_groups) {
                $receipt_amount = $cust_groups->where('paymenttype', 'E')->sum('order_amount');

                if ($receipt_amount > 0) {

                    if ($fields->payment_by == 'C') {
                        $receipt_no = IdGenerator::generate(['table' => 'receipts', 'field' => 'receipt_no', 'length' => 15, 'prefix' => 'RC' . date('Ymd')]);
                        if ($fields->tax_status) {
                            $tax_amount = $receipt_amount * 0.01;
                        } else {
                            $tax_amount = 0;
                        }
                        $receipt = Receipt::create([
                            'receipt_no' => $receipt_no,
                            'receipt_date' => today(),
                            'branch_id' => auth()->user()->branch_id,
                            'customer_id' => $cust,
                            'total_amount' => $receipt_amount,
                            'discount_amount' => $fields->discount_amount,
                            'tax_amount' => $tax_amount,
                            'pay_amount' => $receipt_amount - $fields->discount_amount - $tax_amount,
                            'receipttype' => 'E',
                            'branchpay_by' => $fields->payment_by,
                            'bankaccount_id' => $fields->bankaccount_id,
                            'bankreference' => $fields->reference,
                            'description' => $fields->description,
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                }

                foreach ($cust_groups as $model) {

                    if ($model->order_status <> 'branch warehouse') {
                        return Action::danger('ไม่สามารถยืนยันการรับสินค้ารายการนี้ได้');
                    }

                    $model->order_status = 'completed';
                    $model->trantype = '0';
                    $model->order_recname = $fields->order_recname;
                    $model->idcardno = $fields->idcardno;
                    if ($receipt_amount > 0) {
                        $model->branchpay_by =  $fields->payment_by;

                        if ($model->paymenttype == 'E') {

                            if ($fields->payment_by == 'T') {
                                $model->bankaccount_id = $fields->bankaccount;
                                $model->bankreference = $fields->refernce;
                                $model->payment_status = false;

                                //create bank_transfer
                                Order_banktransfer::create([
                                    'customer_id' => $model->customer_rec_id,
                                    'order_header_id' => $model->id,
                                    'branch_id' => $model->branch_rec_id,
                                    'status' => false,
                                    'transfer_type' => 'E',
                                    'transfer_amount' => $model->order_amount,
                                    'bankaccount_id' => $fields->bankaccount,
                                    'reference' => $fields->reference,
                                    'user_id' => auth()->user()->id,
                                ]);
                            } elseif ($fields->payment_by == 'C') {
                                $model->payment_status = true;
                                //update branch_balance
                                $branch_balance = Branch_balance::where('order_header_id', $model->id)->first();
                                $branch_balance->pay_amount = $model->order_amount;
                                $branch_balance->updated_by = auth()->user()->id;
                                $branch_balance->branchpay_date = today();
                                $branch_balance->payment_status = true;
                                $branch_balance->remark = $fields->description;
                                $branch_balance->receipt_id = $receipt->id;

                                $branch_balance->save();
                            }
                        }
                    }

                    $model->save();
                    Order_status::create([
                        'order_header_id' => $branch_balance->order_header_id,
                        'status' => 'completed',
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
        }

        return Action::push('/resources/branchrec_orders/');
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
            $branchrec_order = Branchrec_order::find($this->model);

            if ($branchrec_order->order_amount > 0 && $branchrec_order->paymenttype == 'E') {
                return [
                    Currency::make('ค่าขนส่งที่ต้องจัดเก็บ', 'order_amount')->default($branchrec_order->order_amount)
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
                    Text::make('ชื่อผู้รับสินค้า', 'order_recname'),
                    Text::make('เลขบัตรประชาชน', 'idcardno'),
                ];
            } else {
                return [];
            }
        }

        return [
            Currency::make('ค่าขนส่งที่ต้องจัดเก็บ', 'order_amount')
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
            Text::make('ชื่อผู้รับสินค้า', 'order_recname'),
            Text::make('เลขบัตรประชาชน', 'idcardno'),
        ];
    }
}
