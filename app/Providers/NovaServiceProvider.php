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
use App\Nova\Metrics\CustomersByDistrict;
use App\Nova\Metrics\CustomersByProvince;
use App\Nova\Metrics\ExpressIncomes;
use App\Nova\Metrics\OrderAllIncomes;
use App\Nova\Metrics\OrderIncomes;
use App\Nova\Metrics\OrdersByBranchRec;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\OrdersByPaymentType;
use App\Nova\Metrics\OrdersPerMonth;
use App\Nova\Metrics\WaybillAmount;
use App\Nova\Metrics\WaybillIncome;
use App\Nova\Metrics\WaybillIncomePerDay;
use App\Nova\Metrics\WaybillLoading;
use App\Nova\Metrics\WaybillPayable;
use App\Nova\Metrics\WaybillsPerDay;
use Dniccum\CustomEmailSender\CustomEmailSender;
use Dniccum\NovaDocumentation\NovaDocumentation;

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

            (new OrderAllIncomes())->width('1/2'),
            (new OrderIncomes())->width('1/2'),

            (new ExpressIncomes())->width('1/2'),
            (new CharterIncomes())->width('1/2'),

            (new OrdersPerMonth())->width('1/2'),
            (new OrdersPerDay())->width('1/2'),

            (new OrdersByPaymentType())->width('1/2'),
            (new OrdersByBranchRec())->width('1/2'),

            (new WaybillsPerDay()),
            (new WaybillLoading()),

            (new WaybillAmount()),
            (new WaybillPayable()),

            (new WaybillIncome()),
            (new WaybillIncomePerDay()),

            (new CustomersPerDay)->width('1/2'),
            (new NewCustomers)->width('1/2'),

            (new CustomersByProvince())->width('1/2'),
            (new CustomersByDistrict())->width('1/2'),
            // ->canSee(function ($request) {
            //     return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view orders-by-payment-type');
            // }),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            //new Sisahygo,
        ];
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
