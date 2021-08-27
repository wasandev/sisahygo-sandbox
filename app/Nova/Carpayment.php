<?php

namespace App\Nova;

use App\Nova\Actions\PrintCarpayment;
use App\Nova\Filters\CarpaymentFromDate;
use App\Nova\Filters\CarpaymentToDate;
use App\Nova\Filters\PaymentType;
use App\Nova\Lenses\accounts\CarpaymentReportByDay;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;

class Carpayment extends Resource
{
    use HasDependencies;
    public static $group = '9.2 งานการเงิน/บัญชี';
    public static $priority = 7;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Carpayment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'payment_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'payment_no'
    ];
    public static function label()
    {
        return __('Car payment');
    }
    public static $searchRelations = [
        'car' => ['car_regist'],
    ];
    public static $globalSearchRelations = [
        'car' => ['car_regist'],

    ];
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            Boolean::make('สถานะ', 'status')
                ->default(true)
                ->readonly(),
            Text::make('เลขที่เอกสาร', 'payment_no')
                ->readonly(),
            Date::make('วันที่เอกสาร', 'payment_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY'),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->default(function () {
                    return auth()->user()->branch_id;
                })->searchable()
                ->hideFromIndex(),
            Select::make('ประเภทการจ่าย', 'type')
                ->options([
                    'T' => 'ค่าบรรทุก',
                    'O' => 'อื่นๆ',
                    'B' => 'ค่าบรรทุกเก็บปลายทาง'
                ])->default('T')
                ->displayUsingLabels()
                ->exceptOnForms(),
            Select::make('ประเภทการจ่าย', 'type')
                ->options([
                    'T' => 'ค่าบรรทุก',
                    'O' => 'อื่นๆ',
                ])->default('T')
                ->displayUsingLabels()
                ->onlyOnForms(),

            BelongsTo::make(__('Car'), 'car', 'App\Nova\Car')
                ->searchable(),
            BelongsTo::make(__('Vendor'), 'vendor', 'App\Nova\Vendor')
                ->exceptOnForms()
                ->hideFromIndex(),
            Text::make(__('Description'), 'description')->default('ค่าบรรทุก(เบิกเดินทาง)'),
            Currency::make(__('Amount'), 'amount'),
            Select::make('จ่ายด้วย', 'payment_by')->options([
                'H' => 'เงินสด',
                'T' => 'เงินโอน',
                'Q' => 'เช็ค',
                'A' => 'รายการปรับปรุงบัญชี'
            ])->displayUsingLabels()
                ->sortable()
                ->hideFromIndex(),
            NovaDependencyContainer::make([
                BelongsTo::make('โอนจากบัญชี', 'bankaccount', 'App\Nova\Bankaccount')
                    ->nullable(),
                Text::make('ไปยังบัญชีเลขที่', 'tobankaccount')
                    ->nullable(),
                BelongsTo::make(__('Bank'), 'tobank', 'App\Nova\Bank')
                    ->nullable(),
                Text::make('ชื่อบัญชี', 'tobankaccountname')
                    ->nullable()

            ])->dependsOn('payment_by', 'T'),

            NovaDependencyContainer::make([
                Text::make(__('Cheque No'), 'chequeno')
                    ->nullable(),
                Text::make(__('Cheque Date'), 'chequedate')
                    ->nullable(),
                BelongsTo::make(__('Cheque Bank'), 'chequebank', 'App\Nova\Bank')
                    ->nullable()
            ])->dependsOn('payment_by', 'Q'),
            Boolean::make('มีภาษีหัก ณ ที่จ่าย', 'tax_flag')->hideFromIndex(),
            Currency::make('จำนวนภาษี', 'taxamount', function () {
                return $this->amount * 0.01;
            })->exceptOnForms()
                ->hideFromIndex(),

            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
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
            new PaymentType,
            new CarpaymentFromDate,
            new CarpaymentToDate,
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
        return [
            new CarpaymentReportByDay(),
        ];
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
            (new PrintCarpayment)->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบสำคัญจ่ายรายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request, $model) {
                    return $request->user()->hasPermissionTo('view car_payments');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_payments');
                }),
        ];
    }
}
