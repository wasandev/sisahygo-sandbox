<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Bankaccount extends Resource
{
    public static $group = '9.2 งานการเงิน/บัญชี';
    public static $priority = 2;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Bankaccount::class;
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit bankaccounts');
    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    // public static $title = 'account_no';

    public function title()
    {
        return   $this->account_no;
    }
    public function subtitle()
    {
        return  $this->bank->name . ' ' . $this->bankbranch;
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'account_no', 'account_name', 'account_type', 'bankbranch'
    ];

    public static $searchRelations = [
        'bank' => ['name'],
    ];
    public static function label()
    {
        return 'ข้อมูลบัญชีธนาคาร';
    }
    public static function singulatLabel()
    {
        return 'บัญชีธนาคาร';
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
            ID::make(__('ID'), 'id'),
            Boolean::make('ใช้สำหรับการโอนค่าขนส่ง', 'defaultflag'),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->nullable()
                ->help('ระบุสาขากรณีเป็นบัญชีของสาขาร่วมบริการที่ใช้สำหรับรับเงินโอน'),
            BelongsTo::make(__('Bank'), 'bank', 'App\Nova\Bank')
                ->showCreateRelationButton()
                ->searchable(),

            Text::make(__('Bank Account no'), 'account_no')
                ->rules('required'),
            Text::make(__('Account name'), 'account_name')
                ->rules('required')
                ->hideFromIndex(),
            Select::make(__('Account type'), 'account_type')
                ->options([
                    'saving' => 'ออมทรัพย์',
                    'current' => 'กระแสรายวัน',
                    'fixed' => 'ฝากประจำ'
                ])->displayUsingLabels()
                ->rules('required')
                ->default('saving')
                ->hideFromIndex(),
            Text::make(__('Bank branch'), 'bankbranch')
                ->rules('required'),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY hh:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY hh:mm')
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
            // (new Actions\ImportBankaccounts)->canSee(function ($request) {
            //     return $request->user()->role == 'admin';
            // }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit bankaccounts');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit bankaccounts');
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
