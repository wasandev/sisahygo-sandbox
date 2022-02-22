<?php

namespace App\Nova;

use App\Models\Bank;
use App\Models\Bankaccount;
use App\Nova\Actions\PrintCarreceive;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Carreceive extends Resource
{
    public static $group = '9.2 งานการเงิน/บัญชี';
    public static $priority = 8;
    public static $with = ['car', 'vendor', 'branch', 'user'];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Carreceive::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'receive_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'receive_no'
    ];
    public static function label()
    {
        return __('Car receive');
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
        $bankaccount = Bankaccount::all()->pluck('account_no', 'id');
        $bank =  Bank::all()->pluck('name', 'id');
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Boolean::make('สถานะ', 'status')
                ->default(true)
                ->readonly(),
            Text::make('เลขที่เอกสาร', 'receive_no')
                ->readonly(),
            Date::make('วันที่เอกสาร', 'receive_date')
                ->default(today())
                ->rules('required')
                ->format('DD/MM/YYYY'),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->default(function () {
                    return auth()->user()->branch_id;
                })->searchable()
                ->hideFromIndex(),
            Select::make('ประเภทการรับ', 'type')
                ->options([
                    'T' => 'ค่าบรรทุก',
                    'B' => 'อื่นๆ',
                ])->default('T')
                ->displayUsingLabels()
                ->hideFromIndex(),

            BelongsTo::make(__('Car'), 'car', 'App\Nova\Car')->searchable(),
            BelongsTo::make(__('Vendor'), 'vendor', 'App\Nova\Vendor')
                ->exceptOnForms(),
            Text::make(__('Description'), 'description')
                ->hideFromIndex(),
            Currency::make(__('Amount'), 'amount'),
            Select::make('รับด้วย', 'receive_by')->options([
                'H' => 'เงินสด',
                'T' => 'เงินโอน',
                'Q' => 'เช็ค',
                'A' => 'รายการตัดบัญชี'
            ])->displayUsingLabels()
                ->sortable()
                ->hideFromIndex(),
            NovaDependencyContainer::make([
                // BelongsTo::make('โอนเข้าบัญชี', 'bankaccount', 'App\Nova\Bankaccount')
                //     ->nullable(),
                Select::make('โอนเข้าบัญชี', 'bankaccount')
                    ->options($bankaccount)
                    ->displayUsingLabels()
                    ->nullable(),
                // Text::make('จากบัญชีเลขที่', 'frombankaccount')
                //     ->nullable(),
                // BelongsTo::make(__('Bank'), 'frombank', 'App\Nova\Bank')
                //     ->nullable(),
                // Select::make(__('Bank'), 'frombank')
                //     ->options($bank)
                //     ->displayUsingLabels()
                //     ->nullable(),
                // Text::make('จากชื่อบัญชี', 'tobankaccountname')
                //     ->nullable()

            ])->dependsOn('receive_by', 'T'),

            NovaDependencyContainer::make([
                Text::make(__('Cheque No'), 'chequeno')
                    ->nullable(),
                Text::make(__('Cheque Date'), 'chequedate')
                    ->nullable(),
                // BelongsTo::make(__('Cheque Bank'), 'chequebank', 'App\Nova\Bank')
                //     ->nullable()
                Select::make(__('Bank'), 'chequebank')
                    ->options($bank)
                    ->displayUsingLabels()
                    ->nullable(),
            ])->dependsOn('receive_by', 'Q'),

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
        return [];
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
            (new PrintCarreceive)->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบสำคัญรับรายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request, $model) {
                    return $request->user()->hasPermissionTo('view car_receives');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_receives');
                }),
        ];
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }
}
