<?php

namespace App\Nova\Actions;

use App\Models\Bankaccount;
use App\Models\Branch_balance;
use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_detail;
use App\Models\Delivery_item;
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
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;

class DeliveryConfirmed extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'delivery_confirmed';
    }
    public function name()
    {
        return 'ยืนยันการจัดส่ง';
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

            if ($model->delivery_status) {
                return Action::danger('รายการนี้ยืนยันไปแล้ว');
            }
            $delivery = Delivery::find($model->delivery_id);
            $delivery_details = Delivery_detail::where('delivery_item_id', $model->id)->get();

            if ($model->payment_amount > 0 && $fields->payment_status) {

                $model->branchpay_by = $fields->payment_by;
                $model->pay_amount = $model->payment_amount;

                if ($fields->payment_by === 'T') {
                    $model->payment_status = false;
                    $model->bankaccount_id = $fields->bankaccount;
                    $model->bankreference = $fields->reference;
                } else {
                    $model->payment_status = true;
                    $receipt_no = IdGenerator::generate(['table' => 'receipts', 'field' => 'receipt_no', 'length' => 15, 'prefix' => 'RC' . date('Ymd')]);
                    $receipt = Receipt::create([
                        'receipt_no' => $receipt_no,
                        'receipt_date' => $fields->paydate,
                        'branch_id' => auth()->user()->branch_id,
                        'customer_id' => $model->customer_id,
                        'total_amount' => $model->payment_amount,
                        'discount_amount' => 0,
                        'tax_amount' => 0,
                        'pay_amount' => $model->payment_amount,
                        'receipttype' => 'E',
                        'branchpay_by' => 'C',
                        'description' => $fields->description,
                        'user_id' => auth()->user()->id,
                    ]);
                    $model->receipt_id = $receipt->id;
                }

                $model->paydate = $fields->paydate;
            }
            $model->description = $fields->description;
            $model->delivery_status = true;

            $model->save();

            foreach ($delivery_details as $delivery_detail) {

                $branch_order = Branchrec_order::find($delivery_detail->order_header_id);
                $branch_order->order_status = 'completed';
                $branch_order->shipper_id = $delivery->sender_id;
                $branch_order->branchpay_by =  $fields->payment_by;
                $delivery_detail->delivery_status = true;
                if ($branch_order->paymenttype === 'E') {

                    if ($fields->payment_by === 'T') {
                        $branch_order->bankaccount_id = $fields->bankaccount;
                        $branch_order->bankreference = $fields->refernce;
                        $branch_order->payment_status = false;

                        $delivery_detail->payment_status = false;
                    } elseif ($fields->payment_by === 'C') {
                        $branch_order->payment_status = true;
                        $branch_order->receipt_flag = true;
                        $branch_order->receipt_id = $receipt->id;
                        $branch_order->payment_status = true;


                        $delivery_detail->payment_status = true;

                        //Branch balance
                        $branch_balance = Branch_balance::where('order_header_id', $branch_order->id)->first();
                        $branch_balance->pay_amount = $branch_order->order_amount;
                        $branch_balance->updated_by = auth()->user()->id;
                        $branch_balance->branchpay_date = $fields->paydate; //แก้ไข
                        $branch_balance->payment_status = true;
                        $branch_balance->remark = $fields->description;
                        $branch_balance->receipt_id = $receipt->id;
                        $branch_balance->save();
                    }
                }
                $branch_order->save();
                $delivery_detail->save();
            }

            //return Action::message('ยืนยันการจัดส่งเรียบร้อยแล้ว');
            return Action::push('/resources/deliveries/' . $model->delivery_id);
        }
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
            $delivery_item = Delivery_item::find($this->model);

            if ($delivery_item->payment_amount > 0) {

                return [
                    Currency::make('ค่าขนส่งที่ต้องจัดเก็บ', 'payment_amount')->default($delivery_item->payment_amount)
                        ->readonly(),
                    Boolean::make('จัดเก็บค่าขนส่งแล้ว', 'payment_status')->rules('required'),
                    NovaDependencyContainer::make([

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
                        //Currency::make('ส่วนลด', 'discount_amount'),
                        //Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),
                    ])->dependsOn('payment_status', true),
                    Text::make('หมายเหตุเพิ่มเติม', 'description')
                ];
            } else {
                return [];
            }
        }
        return [
            Date::make('วันที่รับชำระ', 'paydate')
                ->default(today()->toDateString()),
            Currency::make('ค่าขนส่งที่ต้องเก็บ', 'payment_amount')
                ->readonly(),
            Boolean::make('จัดเก็บค่าขนส่งแล้ว', 'payment_status'),
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
            //Currency::make('ส่วนลด', 'discount_amount'),
            //Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),
        ];
    }
}
