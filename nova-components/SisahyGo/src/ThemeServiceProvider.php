<?php

namespace Wasandev\SisahyGo;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::theme(asset('/wasandev/sisahy-go/theme.css'));
        });

        $this->publishes([
            __DIR__.'/../resources/css' => public_path('wasandev/sisahy-go'),
        ], 'public');
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
}
