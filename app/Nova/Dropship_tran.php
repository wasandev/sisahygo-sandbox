<?php

namespace App\Nova;

use App\Nova\Actions\PrintDropship_tran;
use App\Nova\Actions\ShiptoCenterConfirm;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Dropship_tran extends Resource
{

    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 1.1;
    public static $trafficCop = false;
    public static $preventFormAbandonment = true;
    public static $perPageOptions = [50, 100, 150];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Dropship_tran::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'dropship_tran_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'dropship_tran_no', 'branch_id'
    ];
    public static function label()
    {
        return 'ใบจัดส่งสินค้าจากตัวแทน';
    }
    public static function singularLabel()
    {
        return 'ใบจัดส่งสินค้าจากตัวแทน';
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
            Boolean::make('สถานะ', 'status'),
            BelongsTo::make('จากสาขา', 'branch', 'App\Nova\Branch')
                ->searchable()
                ->readonly(),

            Text::make('เลขที่เอกสาร', 'dropship_tran_no')
                ->readonly()
                ->sortable(),
            Date::make('วันที่', 'dropship_tran_date')
                ->readonly()
                ->sortable(),
            BelongsTo::make('พนักงานจัดส่ง', 'employee', 'App\Nova\Employee')
                ->sortable()
                ->hideFromIndex(),
            Currency::make('ค่าขนส่ง', 'tran_amount'),
            Currency::make('รายได้ของตัวแทน', 'dropship_income'),
            Currency::make('เงินสดรับต้นทาง', 'scash_amount'),
            Currency::make('เงินเก็บปลายทาง', 'dcash_amount'),
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
            HasMany::make('ใบรับส่ง', 'order_dropships', 'App\Nova\Order_dropship'),
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
            (new ShiptoCenterConfirm())
                ->onlyOnDetail()
                ->canRun(function ($request, $model) {
                    return $request->user()->hasPermissionTo('manage order_headers');
                })
                ->canSee(function ($request) {
                    $branch = \App\Models\Branch::find($request->user()->branch_id);
                    return !($branch->dropship_flag);
                }),
            (new PrintDropship_tran())
                ->onlyOnDetail(),
        ];
    }
}
