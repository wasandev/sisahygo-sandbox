<?php

namespace App\Nova;

use App\Nova\Actions\InvoiceReceipt;
use App\Nova\Actions\PrintInvoice;
use App\Nova\Filters\ArbalanceByCustomer;
use App\Nova\Filters\InvoiceFromDate;
use App\Nova\Filters\InvoiceNotReceipt;
use App\Nova\Filters\InvoiceToDate;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Http\Requests\NovaRequest;

class Invoice extends Resource
{
    public static $group = '9.1 งานลูกหนี้การค้า';
    public static $priority = 3;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoice::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'invoice_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'invoice_no',
    ];

    public static $searchRelations = [
        'ar_customer' => ['name'],
    ];
    public static $globalSearchRelations = [
        'ar_customer' => ['name'],
    ];
    public static function label()
    {
        return 'รายการใบแจ้งหนี้';
    }
    public static function singularLabel()
    {
        return 'ใบแจ้งหนี้';
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Status::make(__('Status'), 'status')
                ->loadingWhen(['new'])
                ->failedWhen(['cancel'])
                ->exceptOnForms()
                ->sortable(),
            Date::make('วันที่แจ้งหนี้', 'invoice_date')
                ->sortable()
                ->readonly(),
            Date::make('วันที่ครบกำหนด', 'due_date'),
            Text::make('เลขที่ใบแจ้งหนี้', 'invoice_no')
                ->sortable()
                ->readonly(),
            BelongsTo::make(__('Customer'), 'ar_customer', 'App\Nova\Ar_customer')
                ->sortable()
                ->searchable()
                ->readonly(),

            Number::make('จำนวนเงิน', 'invoice_amount', function () {
                if (isset($this->ar_balances)) {
                    return number_format($this->ar_balances->sum('ar_amount'), 2, '.', ',');
                } else {
                    return
                        number_format(0, 2, '.', ',');
                }
            })->exceptOnForms(),
            BelongsTo::make('ใบเสร็จรับเงิน', 'receipt_ar', 'App\Nova\Receipt_ar')
                ->exceptOnForms()
                ->nullable(),
            Text::make('รายละเอียด/หมายเหตุอื่นๆ', 'description')
                ->hideFromIndex(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail()
                ->searchable()
                ->sortable(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            HasMany::make('รายการใบรับส่ง', 'ar_balances', 'App\Nova\Ar_balance')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new ArbalanceByCustomer,
            new InvoiceNotReceipt,
            new InvoiceFromDate,
            new InvoiceToDate,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new PrintInvoice),
            (new InvoiceReceipt)
                ->showOnIndex()
                ->confirmText('ต้องการรับชำระหนี้จากใบแจ้งหนี้ที่เลือกไว้')
                ->confirmButtonText('รับชำระ')
                ->cancelButtonText("ยกเลิก")
                ->canRun(function ($request, $model) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('edit receipt_ar');
                }),


        ];
    }
}
