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
use App\Nova\Metrics\CustomersByDistrict;
use App\Nova\Metrics\CustomersByProvince;
use App\Nova\Metrics\OrdersPerDay;

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
            (new OrdersPerDay)->width('1/2'),
            (new CharterJobsPerDay)->width('1/2'),
            (new CustomersPerDay)->width('1/2'),
            (new NewCustomers)->width('1/2'),
            (new CustomersByProvince())->width('1/2'),
            (new CustomersByDistrict())->width('1/2'),

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
