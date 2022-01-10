<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Anaseqal\NovaImport\NovaImport;
use App\Nova\Dashboards\AcDashboard;
use App\Nova\Dashboards\AdminDashboard;
use App\Nova\Dashboards\ArDashboard;
use App\Nova\Dashboards\BillingDashboard;
use App\Nova\Dashboards\BranchDashboard;
use App\Nova\Dashboards\CheckerDashboard;
use App\Nova\Dashboards\FnDashboard;
use App\Nova\Dashboards\LoaderDashboard;
use App\Nova\Dashboards\MkDashboard;
use App\Nova\Dashboards\ReportDashboard;
use App\Nova\Dashboards\TruckDashboard;
use App\Nova\Metrics\WaybillIncome;
use App\Nova\Metrics\WaybillIncomePerDay;
use App\Nova\Metrics\WaybillLoading;
use App\Nova\Metrics\WaybillPayable;
use App\Nova\Metrics\WaybillsPerDay;
use App\Nova\Metrics\OrderAllIncomes;
use App\Nova\Metrics\OrderIncomes;
use App\Nova\Metrics\OrdersByBranchRec;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\OrdersByPaymentType;
use App\Nova\Metrics\OrdersPerMonth;
use App\Nova\Metrics\WaybillAmount;
use App\Nova\Metrics\CustomersByDistrict;
use App\Nova\Metrics\CustomersByProvince;
use App\Nova\Metrics\ExpressIncomes;
use App\Nova\Metrics\CustomersPerDay;
use App\Nova\Metrics\NewCustomers;
use App\Nova\Metrics\CharterIncomes;
use App\Nova\Metrics\OrderByCheckerPartition;
use App\Nova\Metrics\OrderByUserPartition;
use App\Nova\Metrics\WaybillAmountPerDay;
use App\Nova\Metrics\WaybillPayablePerDay;
use Dniccum\CustomEmailSender\CustomEmailSender;
use Dniccum\NovaDocumentation\NovaDocumentation;
use Wasandev\Account\Account;
use Wasandev\Araccount\Araccount;
use Wasandev\Billing\Billing;
use Wasandev\Branch\Branch;
use Wasandev\Checkers\Checkers;
use Wasandev\Financial\Financial;
use Wasandev\Loading\Loading;
use Wasandev\Marketing\Marketing;
use Wasandev\Orderstatus\Orderstatus;
use Wasandev\Report\Report;
use Wasandev\Sender\Sender;
use Wasandev\Truck\Truck;

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
            (new Checkers())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view checkercards');
            }),
            (new Billing())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view billingcards');
            }),
            (new Loading())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view loadingcards');
            }),
            (new Araccount())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view arbalancecards');
            }),
            (new Financial())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view financialcards');
            }),
            (new Marketing())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view marketingcards');
            }),
            (new Account())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view accountcards');
            }),
            (new Truck())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view truckcards');
            }),
            (new Branch())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view branchcards');
            }),
            (new Sender())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('view sendercards');
            }),

            //admin
            (new OrderAllIncomes())->width('1/2'),
            (new OrderIncomes())->width('1/2'),
            (new ExpressIncomes())->width('1/2'),
            (new CharterIncomes())->width('1/2'),

            (new OrdersPerMonth())->width('1/2'),
            (new OrdersPerDay())->width('1/2'),

            (new OrdersByPaymentType())->width('1/2'),
            (new OrdersByBranchRec())->width('1/2'),

            (new WaybillsPerDay())->width('1/2'),
            (new WaybillLoading())->width('1/2'),

            (new WaybillAmountPerDay())->width('1/2'),
            (new WaybillPayablePerDay())->width('1/2'),

            //(new WaybillIncome())->width('1/2'),
            (new WaybillIncomePerDay())->width('1/2'),

            (new CustomersPerDay())->width('1/2'),
            (new NewCustomers())->width('1/2'),
            (new CustomersByProvince())->width('1/2'),
            (new CustomersByDistrict())->width('1/2'),



            (new Orderstatus())
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
            (new AdminDashboard())
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin' || $request->user()->hasPermissionTo('view admindashboards');
                }),
            (new CheckerDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view checkerdashboards');
                }),
            (new BillingDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view billingdashboards');
                }),
            (new LoaderDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view loaderdashboards');
                }),
            (new ArDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view ardashboards');
                }),
            (new FnDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view fndashboards');
                }),
            (new MkDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view mkdashboards');
                }),
            (new AcDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view acdashboards');
                }),
            (new TruckDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view truckdashboards');
                }),
            (new BranchDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view branchdashboards');
                }),
            (new ReportDashboard())
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('view reportdashboards');
                }),
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
            new NovaDocumentation,
            \Mirovit\NovaNotifications\NovaNotifications::make()
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
