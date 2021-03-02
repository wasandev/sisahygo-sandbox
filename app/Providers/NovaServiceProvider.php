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
use App\Nova\Metrics\OrderIncomes;
use App\Nova\Metrics\OrdersByBranchRec;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\OrdersByPaymentType;
use App\Nova\Metrics\OrdersPerMonth;
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
            (new OrderIncomes())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view order-incomes');
                }),
            (new CharterIncomes())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view charter-incomes');
                }),

            (new OrdersPerMonth())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view orders-per-day');
                }),
            (new CharterJobsPerDay)->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view charter-jobs-per-day');
                }),
            (new CustomersPerDay)->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view customers-per-day');
                }),
            (new NewCustomers)->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view new-customers');
                }),
            (new CustomersByProvince())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view customers-by-province');
                }),
            (new CustomersByDistrict())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view customers-by-district');
                }),
            (new OrdersByPaymentType())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view orders-by-payment-type');
                }),
            (new OrdersByBranchRec())->width('1/2')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view orders-by-payment-type');
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
