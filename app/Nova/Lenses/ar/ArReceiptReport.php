<?php

namespace App\Nova\Lenses\ar;

use App\Nova\Actions\Accounts\PrintArReceiptReport;
use App\Nova\Filters\ArbalanceFromDate;
use App\Nova\Filters\ArbalanceToDate;
use App\Nova\Filters\ArFilterByBranch;
use App\Nova\Filters\ReceiptFromDate;
use App\Nova\Filters\ReceiptToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
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
                    ->join('ar_balances', 'ar_balances.customer_id', '=', 'customers.id')
                    ->where('ar_balances.doctype', '=', 'R')
                    ->orderBy('customers.id', 'asc')
                    ->groupBy('customers.id', 'customers.name')

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
            'customers.id',
            'customers.name',
            DB::raw("SUM(CASE WHEN ar_balances.doctype = 'R' THEN ar_balances.ar_amount ELSE 0 END) as rec_amount"),

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
            ID::make(__('ID'), 'id')->sortable(),
            Text::make('ชื่อลูกค้า', 'name'),
            Currency::make('จำนวนเงินรับชำระ', 'rec_amount'),

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
            // (new NovaSearchableBelongsToFilter('ตามลูกค้า'))
            //     ->fieldAttribute('ar_customer')
            //     ->filterBy('customer_id'),
            new ArFilterByBranch,
            new ArbalanceFromDate,
            new ArbalanceToDate,
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
