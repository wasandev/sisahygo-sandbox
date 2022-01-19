<?php

namespace App\Nova\Actions;

use App\Models\Order_header;
use App\Models\Receipt;
use App\Models\Receipt_ar;
use App\Models\Receipt_item;
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

class ConfirmBanktransfer extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'confirm-banktransfer';
    }
    public function name()
    {
        return __('Confirm bank transfer');
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
            return data_get($item, 'status') == false;
        });
        if ($select_items->isNotEmpty()) {
            $cust_groups = $select_items->groupBy('customer_id')->all();
            $rec_custs = $cust_groups;
            foreach ($rec_custs as $rec_cust => $cust_groups) {

                $receipt_no = IdGenerator::generate(['table' => 'receipts', 'field' => 'receipt_no', 'length' => 15, 'prefix' => 'RC' . date('Ymd')]);
                if ($fields->tax_status) {
                    $tax_amount = $cust_groups->sum('transfer_amount') * 0.01;
                } else {
                    $tax_amount = 0;
                }

                $tranitem = $cust_groups->firstWhere('customer_id', $rec_cust);

                if ($tranitem->order_header->paymenttype == 'T') {
                    $receipttype = 'H';
                } elseif ($tranitem->order_header->paymenttype == 'E') {
                    $receipttype = 'E';
                } else {
                    $receipttype = 'B';
                }



                $receipt = Receipt::create([
                    'receipt_no' => $receipt_no,
                    'receipt_date' => today(),
                    'branch_id' => auth()->user()->branch_id,
                    'customer_id' => $rec_cust,
                    'total_amount' => $cust_groups->sum('transfer_amount'),
                    'discount_amount' => 0,
                    'tax_amount' => $tax_amount,
                    'pay_amount' => $cust_groups->sum('transfer_amount'),
                    'receipttype' => $receipttype,
                    'branchpay_by' => 'T',
                    'bankaccount_id' => $tranitem->bankaccount_id,
                    'bankreference' => $tranitem->reference,
                    'description' => 'ค่าขนส่งสินค้า',
                    'user_id' => auth()->user()->id,
                ]);
                foreach ($cust_groups as $model) {
                    Receipt_item::create([
                        'receipt_id' => $receipt->id,
                        'order_header_id' => $model->order_header_id,
                        'user_id' => auth()->user()->id,
                    ]);

                    //$model->transfer_type = $receipttype;
                    $model->status = true;

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
