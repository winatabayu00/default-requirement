<?php

namespace Winata\Core;

class BaseServiceProvider
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
        $this->publishes([__DIR__.'/../stubs/ResponseCode.php' => app_path('Enums/ResponseCode')]);
    }
}
