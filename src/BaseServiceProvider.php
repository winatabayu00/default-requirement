<?php

namespace Winata\Core;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        copy(from: __DIR__.'/../stubs/ResponseCode.stub', to: app_path('Enums/ResponseCode.php'));
    }
}
