<?php

namespace App\Nova;


use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Serviceprice extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 9;


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Serviceprice';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';
    public static $priceitem = 'App\Nova\Serviceprice_item';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];
    public static function label()
    {
        return __('Parcels shipping costs');
    }
    public static function singularLabel()
    {
        return __('Parcels shipping cost');
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
            ID::make()->sortable(),
            Boolean::make(__('Status'), 'status'),

            Text::make(__('Name'), 'name'),
            Select::make('เงื่อนไขการคิดราคา', 'pricetypes')
                ->options([
                    'size' => 'ขนาด(กว้าง+ยาว+สูง)-ซม.',
                    'weight' => 'น้ำหนัก(กก.)',
                    'sizeorweight' => 'ขนาดและน้ำหนัก(ใช้อันที่มากกว่า)',
                ])->displayUsingLabels(),
            Number::make('ราคาเริ่มต้น/ชิ้น - บาท', 'startrate'),
            Number::make('คิดเพิ่มขนาดเกินมาตราฐาน - บาท/ขนาด/กก.', 'oversizerate')
                ->hideFromIndex(),
            Date::make('วันที่เริ่มใช้งาน', 'start_date')
                ->hideFromIndex(),
            Date::make('วันที่สิ้นสุดการใช้งาน', 'end_date')
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
            HasMany::make('รายการราคา', 'serviceprice_items', 'App\Nova\Serviceprice_item'),

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
            new Actions\AddServicepriceItem,

        ];
    }
}
