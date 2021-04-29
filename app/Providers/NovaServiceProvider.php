<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Nova\Dashboards\Sisahygo;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Nova\Metrics\CustomersPerDay;
use App\Nova\Metrics\NewCustomers;
use App\Nova\Metrics\CharterJobsPerDay;
use Anaseqal\NovaImport\NovaImport;
use App\Nova\Metrics\CharterIncomes;
use App\Nova\Metrics\CheckerbyUser;
use App\Nova\Metrics\CheckerCancelbyUser;
use App\Nova\Metrics\CheckerProblembyUser;
use App\Nova\Metrics\CustomersByDistrict;
use App\Nova\Metrics\CustomersByProvince;
use App\Nova\Metrics\ExpressIncomes;
use App\Nova\Metrics\LoaderbyUser;
use App\Nova\Metrics\OrderAllIncomes;
use App\Nova\Metrics\OrderbyUser;
use App\Nova\Metrics\OrderCashbyUser;
use App\Nova\Metrics\OrderCashUser;
use App\Nova\Metrics\OrderIncomes;
use App\Nova\Metrics\OrdersByBranchRec;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\OrdersByPaymentType;
use App\Nova\Metrics\OrdersPerMonth;
use App\Nova\Metrics\WaybillAmount;
use App\Nova\Metrics\WaybillbyLoader;
use App\Nova\Metrics\WaybillIncome;
use App\Nova\Metrics\WaybillIncomePerDay;
use App\Nova\Metrics\WaybillLoading;
use App\Nova\Metrics\WaybillPayable;
use App\Nova\Metrics\WaybillsPerDay;
use Dniccum\CustomEmailSender\CustomEmailSender;
use Dniccum\NovaDocumentation\NovaDocumentation;

use Wasandev\Checkers\Checkers;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            // return in_array($user->email, [
            //     //
            // ]);
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            (new CheckerbyUser())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('view order_checkers');
            }),
            (new CheckerCancelbyUser())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('view order_checkers');
            }),
            (new CheckerProblembyUser())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('view order_checkers');
            }),

            (new OrderbyUser())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('manage order_headers');
            }),
            (new OrderCashbyUser())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('manage order_headers');
            }),
            (new LoaderbyUser())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('view order_loaders');
            }),
            (new WaybillbyLoader())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('view waybills');
            }),
            (new OrderAllIncomes())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new OrderIncomes())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),

            (new ExpressIncomes())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new CharterIncomes())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),

            (new OrdersPerMonth())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new OrdersPerDay())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),

            (new OrdersByPaymentType())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new OrdersByBranchRec())->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),

            (new WaybillsPerDay())->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new WaybillLoading())->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),

            (new WaybillAmount())->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new WaybillPayable())->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),

            (new WaybillIncome())->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new WaybillIncomePerDay())->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),

            (new CustomersPerDay)->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new NewCustomers)->width('1/2')->canSee(function ($request) {
                return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
            }),
            (new CustomersByProvince())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
                }),
            (new CustomersByDistrict())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view dashboards');
                }),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [

            \Pktharindu\NovaPermissions\NovaPermissions::make()
                ->roleResource(Role::class),
            new NovaImport,
            (new CustomEmailSender())
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            new NovaDocumentation
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    protected function resources()
    {

        Nova::resourcesIn(app_path('Nova'));
        Nova::sortResourcesBy(function ($resource) {
            return $resource::$priority ?? 9999;
        });
    }
}
