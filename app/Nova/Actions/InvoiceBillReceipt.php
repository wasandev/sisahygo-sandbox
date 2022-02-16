<?php

namespace App\Nova\Actions;

use App\Models\Ar_balance;
use App\Models\Bank;
use App\Models\Bankaccount;
use App\Models\Invoice;
use App\Models\Order_banktransfer;
use App\Models\Order_header;
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
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class InvoiceBillReceipt extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'invoice_bill_receipt';
    }
    public function name()
    {
        return 'รับชำระหนี้บางบิล';
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
            return data_get($item, 'invoice_id') <> 'null' && data_get($item, 'receipt_id') == null;
        });
        if ($select_items->isNotEmpty()) {
            $cust_groups = $select_items->groupBy('customer_id')->all();
            $invoice_custs = $cust_groups;
            foreach ($invoice_custs as $invoice_cust => $cust_groups) {

                $pay_amount = 0;
                foreach ($cust_groups as $invoice_item) {
                    $pay_amount = $pay_amount + $invoice_item->ar_amount;
                }

                if ($pay_amount <> $fields->pay_amount + $fields->discount_amount) {
                    return Action::danger('ยอดเงินรับชำระไม่ถูกต้อง ยอดรับต้องเท่ากับ' . ($pay_amount - $fields->discount_amount));
                }
                $receipt_no = IdGenerator::generate(['table' => 'receipts', 'field' => 'receipt_no', 'length' => 15, 'prefix' => 'RC' . date('Ymd')]);
                if ($fields->tax_status) {
                    $tax_amount =  $pay_amount * 0.01;
                } else {
                    $tax_amount = 0;
                }
                $receipt = Receipt::create([
                    'receipt_no' => $receipt_no,
                    'status' => true,
                    'receipt_date' => $fields->receipt_date,
                    'branch_id' => auth()->user()->branch_id,
                    'customer_id' => $invoice_cust,
                    'total_amount' => $pay_amount,
                    'discount_amount' => $fields->discount_amount,
                    'tax_amount' => $tax_amount,
                    'pay_amount' => $fields->pay_amount,
                    'receipttype' => 'B',
                    'branchpay_by' => $fields->payment_by,
                    'bankaccount_id' => $fields->bankaccount_id,
                    'bankreference' => $fields->bankreference,
                    'chequeno' => $fields->chequeno,
                    'chequedate' => $fields->chequedate,
                    'chequebank_id' => $fields->chequebank,
                    'description' => $fields->description,
                    'user_id' => auth()->user()->id,
                ]);

                Ar_balance::create([
                    'customer_id' => $invoice_cust,
                    'doctype' => 'R',
                    'docno' => $receipt_no,
                    'docdate' => $fields->receipt_date,
                    'description' => 'รับชำระหนี้',
                    'ar_amount' => $pay_amount,
                    'user_id' => auth()->user()->id,
                    'receipt_id' => $receipt->id,

                ]);
                if ($fields->payment_by == "T") {
                    $Order_banktransfer = Order_banktransfer::create([
                        'customer_id' => $invoice_cust,
                        'receipt_id' => $receipt->id,
                        'branch_id' => auth()->user()->branch_id,
                        'status' => true,
                        'transfer_amount' => $fields->pay_amount,
                        'bankaccount_id' => $fields->bankaccount,
                        'reference' => $fields->reference,
                        'transfer_type' => 'B',
                        'user_id' => auth()->user()->id,
                        'transfer_date' => $fields->receipt_date,
                    ]);
                }
                foreach ($cust_groups as $model) {
                    $model->receipt_id = $receipt->id;
                    $model->updated_by = auth()->user()->id;
                    $model->save();
                    $order_header = Order_header::find($model->order_header_id);
                    $order_header->payment_status = true;
                    $order_header->updated_by = auth()->user()->id;
                    $order_header->saveQuietly();
                }
            }
            return Action::message('รับชำระหนี้เรียบร้อยแล้ว');
        } else {
            return Action::danger('รายที่เลือกยังไม่ทำใบแจ้งหนี้หรือรับชำระเรียบร้อยแล้ว');
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
        $banks = Bank::all()->pluck('name', 'id');


        return [
            Currency::make('จำนวนเงินรับชำระ', 'pay_amount'),
            Date::make('วันที่รับชำระ', 'receipt_date'),
            Select::make('รับชำระด้วย', 'payment_by')->options([
                'C' => 'เงินสด',
                'T' => 'เงินโอน',
                'Q' => 'เช็ค'
            ])->displayUsingLabels()
                ->default('C'),
            NovaDependencyContainer::make([
                Select::make(__('Account no'), 'bankaccount')
                    ->options($bankaccount)
                    ->displayUsingLabels()
                    ->rules('required'),
                Text::make(__('Bank reference no'), 'reference'),
            ])->dependsOn('payment_by', 'T'),
            NovaDependencyContainer::make([
                Text::make(__('Cheque No'), 'chequeno')
                    ->nullable(),
                Text::make(__('Cheque Date'), 'chequedate')
                    ->nullable(),
                Select::make(__('Cheque Bank'), 'chequebank')
                    ->options($banks)
                    ->nullable()
            ])->dependsOn('payment_by', 'Q'),
            Currency::make('ส่วนลด', 'discount_amount'),
            Boolean::make('หักภาษี ณ ที่จ่าย', 'tax_status'),
            Text::make('หมายเหตุเพิ่มเติม', 'remark')
        ];
    }
}
