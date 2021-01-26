<?php

namespace App\Nova\Actions;

use App\Models\Order_cash;
use App\Models\Receipt;
use App\Models\Receipt_item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Laravel\Nova\Fields\Boolean;

class OrderCashReceipt extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'order-cash-receipt';
    }
    public function name()
    {
        return 'ออกใบเสร็จรับเงิน';
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
        $select_items = $models->filter(function ($item) {
            return data_get($item, 'receipt_flag') == false;
        });
        if ($select_items->isNotEmpty()) {
            $cust_groups = $select_items->groupBy('customer_id')->all();
            $rec_custs = $cust_groups;
            foreach ($rec_custs as $rec_cust => $cust_groups) {

                $receipt_no = IdGenerator::generate(['table' => 'receipts', 'field' => 'receipt_no', 'length' => 15, 'prefix' => 'RC' . date('Ymd')]);
                if ($fields->tax_status) {
                    $tax_amount = $cust_groups->sum('order_amount') * 0.01;
                }


                $receipt = Receipt::create([
                    'receipt_no' => $receipt_no,
                    'receipt_date' => today(),
                    'branch_id' => auth()->user()->branch_id,
                    'customer_id' => $rec_cust,
                    'total_amount' => $cust_groups->sum('order_amount'),
                    'discount_amount' => 0,
                    'tax_amount' => $tax_amount,
                    'pay_amount' => $cust_groups->sum('order_amount'),
                    'receipttype' => 'H',
                    'branchpay_by' => 'C',
                    'description' => 'ค่าขนส่งสินค้า',
                    'user_id' => auth()->user()->id,
                ]);
                foreach ($cust_groups as $model) {
                    Receipt_item::create([
                        'receipt_id' => $receipt->id,
                        'order_header_id' => $model->id,
                        'user_id' => auth()->user()->id,
                    ]);


                    $model->receipt_flag = true;
                    $model->receipt_id = $receipt->id;
                    $model->save();
                }
            }
            return Action::message('ยืนยันรายการเรียบร้อยแล้ว');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),
        ];
    }
}
