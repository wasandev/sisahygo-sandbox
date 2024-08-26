<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use App\Models\Employee;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Http\Requests\NovaRequest;

class Delivery_costitem extends Resource
{
    use HasDependencies;
    public static $group = '8.สำหรับสาขา';
    public static $priority = 3;
    public static $displayInNavigation = false;   
    public static $globallySearchable = false;
    public static $preventFormAbandonment = true;
    public static $perPageViaRelationship = 100;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Delivery_costitem::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    
    public function title()
    {
        return $this->delivery->delivery_no;

    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id','delivery_id'
    ];

    public static function label()
    {
        return 'ต้นทุนในการจัดส่งสินค้า';
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $employee =  Employee::where('branch_id',$request->user()->branch_id,)->pluck('name', 'id');
       
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('ใบจัดส่ง','delivery','App\Nova\Delivery')
                ->sortable()
                ->rules('required'),
            BelongsTo::make('หมวดค่าใช้จ่าย','company_expense','App\Nova\Company_expense')
                ->sortable()
                ->rules('required'),
            Boolean::make('ต้นทุนด้านแรงงาน', 'personal_costs')
                ->default(false)
                ->hideFromIndex(),
            // BelongsTo::make('ชื่อพนักงาน','employee','App\Nova\Employee')
            //     ->nullable()                
            //     ->showCreateRelationButton(),
           NovaDependencyContainer::make([
                Select::make('ชื่อพนักงาน', 'employee_id')
                    ->options($employee)
                    ->displayUsingLabels()
                    ->nullable(),
                
           ])->dependsOn('personal_costs', true),
            Text::make('รายละเอียด','description'),
            Currency::make('จำนวนเงิน', 'amount')
                ->rules('required'),

            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')                
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->OnlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')               
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

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
        
    }
}
