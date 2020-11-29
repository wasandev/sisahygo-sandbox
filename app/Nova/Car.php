<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\DateTime;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Car extends Resource
{
    use HasDependencies;
    //public static $displayInNavigation = false;
    public static $group = "3.งานด้านรถบรรทุก";
    public static $priority = 4;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Car';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'car_regist';

    public function title()
    {
        //if ($this->cartype) {
        return $this->car_regist . ' ' . $this->cartype->name;
        // }
        //return $this->car_regist;
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'car_regist',
        'car_province'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'owner' => ['name'],
    ];
    public static function label()
    {
        return __('Cars');
    }

    public static function singularLabel()
    {
        return __('Car');
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
            //ID::make()->sortable(),
            Boolean::make('ใช้งาน', 'status'),
            //->hideWhenCreating(),
            Image::make('รูปรถ', 'carimage')->hideFromIndex(),
            new Panel('รายละเอียดของรถ', $this->carFields()),
            new Panel('รายละเอียดอื่นๆของรถ', $this->carotherFields()),
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
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function carFields()
    {
        return [
            Text::make('ทะเบียนรถ', 'car_regist')
                ->rules('required')
                ->sortable(),
            BelongsTo::make('ประเภทรถ', 'cartype', 'App\Nova\Cartype')
                ->showCreateRelationButton()
                ->sortable()
                ->nullable(),
            BelongsTo::make('ลักษณะรถ', 'carstyle', 'App\Nova\Carstyle')
                ->showCreateRelationButton()
                ->sortable()
                ->nullable(),
            BelongsTo::make('จังหวัด', 'province', Province::class)
                ->hideFromIndex()
                ->searchable()
                ->nullable(),
            Text::make('หมายเลขรถของบริษัท', 'carno')
                ->hideFromIndex(),
            Select::make('ตำแหน่งรถ', 'carposition')->options([
                'tractor' => 'หัว',
                'trailer' => 'หาง'
            ])->displayUsingLabels()
                ->sortable()
                ->hideFromIndex(),
            Select::make('การเป็นเจ้าของ', 'ownertype')->options([
                'owner' => 'รถบริษัท',
                'partner' => 'รถร่วมบริการ'
            ])->displayUsingLabels()
                ->sortable(),
            NovaDependencyContainer::make([
                BelongsTo::make('เจ้าของรถ/ผู้รับรายได้', 'owner', 'App\Nova\Vendor')
                    ->showCreateRelationButton()
                    ->sortable()
                    ->searchable(),
            ])->dependsOn('ownertype', 'partner'),

            Date::make('วันที่ได้มา/วันที่เข้าร่วม', 'purchase_date')
                ->hideFromIndex()
                ->format('DD/MM/YYYY'),
            Currency::make('ราคาที่ซื้อมา', 'purchase_price')
                ->hideFromIndex(),

            BelongsTo::make('ตำแหน่งยาง', 'tiretype', 'App\Nova\Tiretype')
                ->hideFromIndex()
                ->nullable()
                ->showCreateRelationButton(),
            Number::make('จำนวนยาง', 'tires')
                ->nullable()
                ->hideFromIndex(),
        ];
    }
    /**
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function carotherFields()
    {
        return [
            Select::make('ประเภทเชื้อเพลง', 'fueltype')->options([
                'diesel' => 'ดีเซล',
                'gasoline' => 'เบนซิน',
                'LPG' => 'LPG',
                'NGV' => 'NGV',
            ])->displayUsingLabels()
                ->sortable()
                ->hideFromIndex(),
            Text::make('ยี่ห้อ', 'carbrand')
                ->hideFromIndex(),
            Text::make('รุ่น', 'carmodel')
                ->hideFromIndex(),
            Text::make('หมายเลขเครื่อง', 'engineno')
                ->hideFromIndex(),
            Text::make('จำนวนซีซี', 'car_cc')
                ->hideFromIndex(),
            Number::make('ปริมาตรรถ', 'car_volumn')
                ->hideFromIndex()
                ->step(0.01),
            Number::make('น้ำหนักรถ', 'car_weight')
                ->hideFromIndex()
                ->step(0.01),
            Number::make('น้ำหนักบรรทุก', 'load_weight')
                ->hideFromIndex()
                ->step(0.01),

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
            (new Actions\ImportCars)->canSee(function ($request) {
                return $request->user()->role == 'admin';
            }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            (new Actions\SetCarType),
            (new Actions\SetCarStyle),
            (new Actions\SetCarOwnerType)

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
