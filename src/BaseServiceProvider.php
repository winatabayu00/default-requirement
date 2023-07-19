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
        copy(from: __DIR__.'/../stubs/ResponseCode.php', to: app_path('Enums/ResponseCode.php'));
    }
}
