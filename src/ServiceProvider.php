<?php

namespace ForTheLocal\Laravel\OpenID;

/**
 * Class ServiceProvider
 * @package ForTheLocal\Laravel\OpenID
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }


}