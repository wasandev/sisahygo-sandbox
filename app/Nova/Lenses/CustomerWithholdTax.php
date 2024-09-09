<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use App\Nova\Filters\ReceiptFromDate;
use App\Nova\Filters\ReceiptToDate;
use App\Nova\Actions\Accounts\PrintTaxwhCustomer;
use Illuminate\Support\Facades\DB;
use App\Nova\Filters\Branch;

class CustomerWithholdTax extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->join('branches','branches.id','=','receipts.branch_id')
                ->join('customers', 'customers.id', '=', 'receipts.customer_id')                
                ->where('receipts.status', '=', '1')
                ->where('receipts.tax_amount','>',0)
                ->orderBy('receipts.receipt_date', 'asc')
                ->orderBy('receipts.branch_id','asc')
                ->orderBy('receipts.receipttype', 'asc')
                ->groupBy('receipts.receipt_date','receipts.branch_id','receipts.receipttype','receipts.branchpay_by', 'receipts.customer_id')
        ));
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        return [
            'receipts.receipt_date',
            'receipts.receipttype',
            'receipts.branchpay_by',
            'customers.name as customer_name',
            'branches.name as branch_name',
            DB::raw('sum(receipts.total_amount) as total_amount'),
            DB::raw('sum(receipts.tax_amount) as tax_amount')

        ];
    }
    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Date::make('วันที่', 'receipt_date')
                ->format('DD/MM/YYYY'),
            Text::make('สาขา','branch_name') ,
            Text::make('ประเภท', function () {
                if ($this->receipttype == 'H') {
                    $rectype = 'เงินสดต้นทาง';
                } elseif ($this->receipttype == 'B') {
                    $rectype = 'วางบิล';
                } else {
                    $rectype = 'ปลายทาง';
                }
                return $rectype;
            }),
            Text::make('ชื่อลูกค้าผู้หักภาษี','customer_name'),
            Currency::make('จำนวนเงินค่าบริการ', 'total_amount', function ($value) {
                return $value;
            }),
            Currency::make('จำนวนเงินภาษีหัก ณ ที่จ่าย', 'tax_amount', function ($value) {
                return $value;
            }),

        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Branch(),
            new ReceiptFromDate(),
            new ReceiptToDate(),
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new PrintTaxwhCustomer($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view receipt_all');
                }),
        ];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'customer-withhold-tax';
    }
     public function name()
    {
        return 'รายการภาษีถูกหัก ณ ที่จ่าย';
    }
}
