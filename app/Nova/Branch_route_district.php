<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use App\Models\Branch_area;
use App\Models\Branch_route;
use Laravel\Nova\Fields\DateTime;



class Branch_route_district extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "5.งานจัดการการขนส่ง";
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Branch_route_district';

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



    public static function label()
    {
        return 'อำเภอในเส้นทางขนส่งภายในสาขา';
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {

        if ($request->editMode == "create"  && !empty($request->viaResource) && !empty($request->viaResourceId) && !empty($request->viaRelationship)) {
            //$branch_route = Branch_route::where('branch_id', $request->viaResourceId)->pluck('name', 'id');
            $branch_route = Branch_route::find($request->viaResourceId);
            $branch_area = Branch_area::where('branch_id', $branch_route->branch_id)->pluck('district', 'id');

            return [
                ID::make(),

                Select::make('เส้นทางขนส่งของสาขา', 'branch_route_id')
                    ->options($branch_route)
                    ->options([$branch_route->id => $branch_route->name])
                    ->displayUsingLabels()
                    ->withMeta(['value' => $branch_route->id])
                    ->hideWhenUpdating()
                    ->readonly(true),
                Select::make('อำเภอ', 'branch_area_id')
                    ->options($branch_area)
                    ->onlyOnForms(),

                Number::make('ระยะทาง(กม.)', 'distance')
                    ->step('0.01'),
            ];
        }

        return [
            ID::make()->sortable(),
            BelongsTo::make('เส้นทางขนส่งของสาขา', 'branch_route', 'App\Nova\Branch_route')
                ->sortable(),

            BelongsTo::make('อำเภอ', 'branch_area', 'App\Nova\Branch_area')
                ->sortable(),

            Number::make('ระยะทางจากสาขา(กม.)', 'distance')
                ->step('0.01'),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->OnlyOnDetail(),
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
        return [];
    }
}
