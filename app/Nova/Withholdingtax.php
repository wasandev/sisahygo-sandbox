<?php

namespace App\Nova;

use App\Models\Incometype;
use App\Models\Vendor;
use App\Nova\Actions\Accounts\PrintWhtaxReport;
use App\Nova\Actions\PrintCarWhtaxForm;
use App\Nova\Filters\WhtaxFromDate;
use App\Nova\Filters\WhtaxToDate;
use App\Nova\Filters\WhtaxType;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;


class Withholdingtax extends Resource
{
    use HasDependencies;
    public static $group = '9.3 งานภาษีหัก ณ ที่จ่าย';
    public static $priority = 2;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Withholdingtax::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit withholdingtaxes');
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $searchRelations = [
        'vendor' => ['name'],
    ];
    public static function label()
    {
        return 'รายการภาษีหัก ณ ที่จ่าย';
    }
    public static function singulatLabel()
    {
        return 'ภาษีหัก ณ ที่จ่าย';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $vendortype1 = Vendor::where('type', 'person')->pluck('name', 'id');
        $vendortype2 = Vendor::where('type', 'company')->pluck('name', 'id');
        $payertype1 = Incometype::where('payertype', '1')->pluck('name', 'id');
        $payertype2 = Incometype::where('payertype', '2')->pluck('name', 'id');

        return [
            ID::make(__('ID'), 'id')->sortable(),
            Date::make('วันที่จ่าย', 'pay_date')->sortable(),
            Select::make('ประเภทผู้ถูกหักภาษี', 'payertype')
                ->options([
                    '1' => 'บุคคลธรรมดา',
                    '2' => 'นิติบุคคล'
                ])->displayUsingLabels()
                ->rules('required')
                ->default('1')
                ->sortable(),
            BelongsTo::make('ประเภทเงินได้', 'incometype', 'App\Nova\Incometype')
                ->onlyOnIndex(),
            BelongsTo::make('ผู้ถูกหักภาษี', 'vendor', 'App\Nova\Vendor')
                ->onlyOnIndex()
                ->sortable(),
            Text::make('ภงด.', function () {
                return $this->incometype->taxform;
            }),
            NovaDependencyContainer::make([
                Select::make('จ่ายให้', 'vendor_id')
                    ->options($vendortype1)
                    ->displayUsingLabels()
                    ->searchable(),

                Select::make('ประเภทเงินได้', 'incometype_id')
                    ->options($payertype1)
                    ->displayUsingLabels()
                    ->searchable(),
            ])->dependsOn('payertype', '1'),
            NovaDependencyContainer::make([
                Select::make('จ่ายให้', 'vendor_id')
                    ->options($vendortype2)
                    ->displayUsingLabels()
                    ->searchable(),
                Select::make('ประเภทเงินได้', 'incometype_id')
                    ->options($payertype2)
                    ->displayUsingLabels()
                    ->searchable(),
            ])->dependsOn('payertype', '2'),
            Text::make('รายละเอียด', 'description')->hideFromIndex(),
            Currency::make('จำนวนเงินจ่าย', 'pay_amount')
                ->sortable(),
            Number::make('อัตราภาษี', function () {
                $incometype = Incometype::find($this->incometype_id);
                return $incometype->taxrate . '%';
            })->onlyOnDetail(),

            Currency::make('ภาษีที่หักไว้', 'tax_amount')->hideWhenCreating()
                ->sortable(),
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
            new WhtaxFromDate(),
            new WhtaxToDate(),
            new WhtaxType()

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

            (new PrintCarWhtaxForm($request->filters))
                ->onlyOnTableRow()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit withholdingtaxes');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit withholdingtaxes');
                }),
            (new PrintWhtaxReport($request->filters))
                ->standalone()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit withholdingtaxes');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit withholdingtaxes');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit withholdingtaxes');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit withholdingtaxes');
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
