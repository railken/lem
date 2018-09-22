<?php

namespace Railken\Laravel\Manager;

use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    public $app;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->commands([Commands\Generate::class]);
    }
}
