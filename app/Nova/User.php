<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\Branch;
use App\Nova\Filters\CurrentUser;
use App\Nova\Filters\UserDepartment;
use Laravel\Nova\Fields\Avatar;

class User extends Resource
{
    public static $group = '1.งานสำหรับผู้ดูแลระบบ';
    public static $priority = 4;
    //public static $showColumnBorders = true;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Users');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('User');
    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public function subtitle()
    {
        if (isset($this->department->name)) {
            return $this->department->name;
        } else {
            return null;
        }
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

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

            //Gravatar::make()->maxWidth(50),

            Avatar::make('รูปโปรไฟล์', 'avatar'),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('Email'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                })
                ->hideFromIndex(),

            Password::make(__('password'), 'password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->sortable()
                ->nullable()
                ->showCreateRelationButton()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            BelongsTo::make('ฝ่าย/แผนก', 'department', 'App\Nova\Department')
                ->sortable()
                ->nullable()
                ->showCreateRelationButton(),

            BelongsTo::make('รายการสาขาปลายทาง(ที่ทำงานประจำ)', 'branch_rec', 'App\Nova\Branch')
                ->sortable()
                ->nullable()
                ->showCreateRelationButton(),
            Select::make(__('Role'), 'role')->options([
                'employee' => 'พนักงาน',
                'admin' => 'Admin',
                'customer' => 'ลูกค้า',
                'driver' => 'พนักงานขับรถ'
            ])->displayUsingLabels()
                ->rules('required')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            Text::make(__('User Code'), 'usercode')
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),

            BelongsTo::make(__('Employee'), 'assign_user', 'App\Nova\Employee')
                ->nullable()
                ->showCreateRelationButton()
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            BelongsTo::make(__('Customer'), 'assign_customer', 'App\Nova\Customer')
                ->nullable()
                ->searchable()
                ->showCreateRelationButton()
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            BelongsToMany::make(__('Roles'), 'roles', \Pktharindu\NovaPermissions\Nova\Role::class)
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            BelongsTo::make(__('Created by'), 'user_create', 'App\Nova\User')
                ->OnlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->OnlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            DateTime::make('Login ล่าสุด', 'logged_in_at')
                ->format('DD/MM/YYYY HH:mm')
                ->sortable()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),

            DateTime::make('Log out ล่าสุด', 'logged_out_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            Text::make('IP Address', 'ip_address')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
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
            new Branch,
            new UserDepartment,
            new CurrentUser
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
            (new Actions\SetUserDepartment)->canSee(function ($request) {
                return $request->user()->role == 'admin';
            }),
            (new Actions\SetUserBranch)
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->role != 'admin') {
            return $query->where('role', '<>', 'admin');
        }
        return $query;
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
