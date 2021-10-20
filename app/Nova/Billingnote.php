<?php

namespace App\Nova;

use App\Nova\Actions\BillingnoteConfirmed;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Billingnote extends Resource
{
    public static $group = '9.1 งานลูกหนี้การค้า';
    public static $priority = 5;
    public static $globallySearchable = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Billingnote::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $searchRelations = [
        'customer' => ['name'],
    ];

    public static function label()
    {
        return 'เอกสารการวางบิล';
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
            Badge::make('สถานะ', 'status')->map([
                'new' => 'info',
                'billed' => 'warning',
                'completed' => 'success'
            ]),
            BelongsTo::make('ชื่อลูกค้า', 'ar_customer', 'App\Nova\Ar_customer')
                ->sortable(),
            Text::make('อีเมล์', function () {
                return $this->ar_customer->email;
            }),
            //->searchable(),
            Date::make('วันที่วางบิล', 'billingnote_date')
                ->sortable()
                ->default(today()),
            Select::make('วิธีวางบิล', 'billing_by')->options(
                [
                    '1' => 'อีเมล์',
                    '2' => 'ไปรษณีย์',
                    '3' => 'พนักงานวางบิล'
                ]
            )->displayUsingLabels(),
            DateTime::make('วันนัดชำระ', 'set_payment_date')->nullable(),
            Text::make('รายละเอียด', 'description')->nullable(),
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
            HasMany::make('รายการใบแจ้งหนี้', 'billingnote_items', 'App\Nova\Billingnote_item'),
            HasMany::make('เอกสารแนบ', 'billingnote_files', 'App\Nova\Billingnote_file')



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
            (new BillingnoteConfirmed)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit billingnotes');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit billingnotes');
                }),
        ];
    }

    public static function relatableAr_customers(NovaRequest $request, $query)
    {

        return $query->wherehas('invoices', function ($query) {
            $query->where('status',  'new');
        });
    }
}
