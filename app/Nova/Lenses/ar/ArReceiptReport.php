<?php

namespace App\Nova\Lenses\ar;

use App\Nova\Actions\Accounts\PrintArReceiptReport;
use App\Nova\Filters\Customer;
use App\Nova\Filters\ReceiptByCustomer;
use App\Nova\Filters\ReceiptFromDate;
use App\Nova\Filters\ReceiptToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Suenerds\NovaSearchableBelongsToFilter\NovaSearchableBelongsToFilter;

class ArReceiptReport extends Lens
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
        return $request->withOrdering(
            $request->withFilters(
                $query->select(self::columns())
                    ->where('status', '=', true)
                    ->where('receipttype', '=', 'B')
                    ->orderBy('receipt_date', 'asc')
                    ->orderBy('customer_id', 'asc')
                    ->groupBy('receipt_date', 'customer_id')
            )

        );
    }
    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        return [
            'receipt_date',
            'customer_id',
            DB::raw('sum(total_amount) as amount'),
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
            Date::make('วันที่', 'receipt_date'),
            BelongsTo::make('ชื่อลูกค้า', 'ar_customer', 'App\Nova\Ar_customer'),
            Currency::make('จำนวนเงิน', 'amount'),

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
            (new NovaSearchableBelongsToFilter('ตามลูกค้า'))
                ->fieldAttribute('ar_customer')
                ->filterBy('customer_id'),
            new ReceiptFromDate,
            new ReceiptToDate,
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
            (new PrintArReceiptReport($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view ar_balance');
                }),
            (new DownloadExcel)->allFields()
                ->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view ar_balance');
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
        return 'ar-receipt-report';
    }
    public function name()
    {
        return 'รายงานรับชำระหนี้ลูกหนี้การค้า';
    }
}
