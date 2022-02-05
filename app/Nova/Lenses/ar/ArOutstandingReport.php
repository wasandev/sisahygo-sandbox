<?php

namespace App\Nova\Lenses\ar;

use App\Nova\Actions\Accounts\PrintArCardReport;
use App\Nova\Actions\Accounts\PrintArOutstandingReport;
use App\Nova\Filters\ArbalanceByCustomer;
use App\Nova\Filters\ArbalanceFromDate;
use App\Nova\Filters\ArbalanceToDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Suenerds\NovaSearchableBelongsToFilter\NovaSearchableBelongsToFilter;

class ArOutstandingReport extends Lens
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
                ->join('customers', 'ar_balances.customer_id', '=', 'customers.id')
                ->join('order_headers', 'ar_balances.order_header_id', '=', 'order_headers.id')
                ->leftJoin('invoices', 'ar_balances.invoice_id', '=', 'invoices.id')
                ->where('order_headers.payment_status', '=', false)
                ->where('ar_balances.doctype', '=', 'P')
                ->orderBy('customers.id', 'asc')
                ->groupBy(
                    'ar_balances.id',
                    'ar_balances.customer_id',
                    'customers.name',
                    'ar_balances.docno',
                    'ar_balances.docdate',
                    'invoices.invoice_no',
                    'invoices.invoice_date',
                    'invoices.due_date',
                    'ar_balances.ar_amount'
                )

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
            'ar_balances.id',
            'ar_balances.customer_id',
            'customers.name',
            'ar_balances.docno',
            'ar_balances.docdate',
            'invoices.invoice_no',
            'invoices.invoice_date',
            'invoices.due_date',
            'ar_balances.ar_amount'
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
            Text::make('เอกสารตั้งหนี้', 'docno'),
            Date::make('วันที่ตั้งหนี้', 'docdate'),
            Text::make('เลขที่ใบแจ้งหนี้', 'invoice_no'),
            Text::make('วันครบกำหนด', 'due_date'),
            Currency::make('จำนวนเงิน', 'ar_amount'),

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
            new ArbalanceToDate
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
            (new PrintArOutstandingReport($request->filters))
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
        return 'ar-outstanding-report';
    }
    public function name()
    {
        return 'รายงานลูกหนี้ค้างชำระ';
    }
}
