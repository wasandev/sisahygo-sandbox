<?php

namespace App\Nova;

use App\Nova\Filters\Branch;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Http\Requests\NovaRequest;

class Delivery extends Resource
{
    public static $group = '8.สำหรับสาขา';
    public static $priority = 3;
    // public static $polling = true;
    // public static $pollingInterval = 90;
    // public static $showPollingToggle = true;
    //public static $globallySearchable = true;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Delivery::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'delivery_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'delivery_no',
    ];
    public static function label()
    {
        return 'รายการจัดส่งสินค้า';
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
            ID::make(__('ID'), 'id')
                ->sortable()
                ->hideFromIndex(),
            Boolean::make(__('Status'), 'completed'),
            Text::make('เลขที่จัดส่ง', 'delivery_no')
                ->readonly(),
            Date::make('วันที่จัดส่ง', 'delivery_date'),
            Select::make('ประเภทการจัดส่ง', 'delivery_type')->options([
                '0' => 'รถบรรทุกจัดส่ง',
                '1' => 'สาขาจัดส่ง'
            ])->displayUsingLabels()
                ->exceptOnForms(),
            BelongsTo::make('พนักงานจัดส่ง', 'sender', 'App\Nova\User')
                ->hideFromIndex()
                ->rules('required'),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->onlyOnDetail(),
            BelongsTo::make(__('Car regist'), 'car', 'App\Nova\Car')
                ->exceptOnForms(),
            BelongsTo::make(__('Driver'), 'driver', 'App\Nova\Employee')
                ->hideFromIndex(),
            Text::make(__('Description'), 'description')
                ->hideFromIndex(),
            Currency::make('จำนวนเงินจัดเก็บ', 'receipt_amount')
                ->readonly(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            HasMany::make('รายการจัดส่งตามผู้รับ', 'delivery_items', 'App\Nova\Delivery_item'),

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
            new Branch()
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
            (new Actions\PrintDelivery)->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบจัดส่งสินค้ารายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request, $model) {
                    return true;
                }),
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->role == 'driver') {
            return   $query->where('branch_id', $request->user()->branch_id)
                ->where('sender_id', $request->user()->id);
        } elseif ($request->user()->branch->code <> '001') {
            return  $query->where('branch_id', $request->user()->branch_id);
        } else {
            return $query;
        }
    }
}
