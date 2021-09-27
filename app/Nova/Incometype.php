<?php

namespace App\Nova;


use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Incometype extends Resource
{
    public static $group = '9.3 งานภาษีหัก ณ ที่จ่าย';
    public static $priority = 1;

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit incometypes');
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Incometype::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';



    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'code'
    ];

    public static function label()
    {
        return 'กำหนดประเภทเงินได้';
    }
    public static function singulatLabel()
    {
        return 'ประเภทเงินได้';
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

            Text::make('รหัสรายได้', 'code')
                ->rules('required'),
            Text::make('ชื่อรายได้', 'name')
                ->rules('required'),
            Select::make('ผู้ถูกหักภาษี', 'payertype')
                ->options([
                    '1' => 'บุคคลธรรมดา',
                    '2' => 'นิติบุคคล'
                ])->displayUsingLabels()
                ->rules('required')
                ->default('1'),
            Number::make('อัตราภาษี(%)', 'taxrate')->step(0.01),
            Select::make('แบบ ภ.ง.ด.', 'taxform')
                ->options([
                    '1' => 'ภ.ง.ด.1',
                    '2' => 'ภ.ง.ด.2',
                    '3' => 'ภ.ง.ด.3',
                    '53' => 'ภ.ง.ด.53'
                ])->displayUsingLabels()
                ->rules('required')
                ->default('3'),
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
            (new DownloadExcel)->allFields()->withHeadings()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit banks');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit banks');
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
