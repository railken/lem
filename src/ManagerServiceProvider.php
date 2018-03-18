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
     * List of all version accepted.
     *
     * @var array
     */
    public $versions = ['5'];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([Commands\Generate::class, Commands\GenerateAttribute::class]);

        $v = explode('.', $this->app->version());

        if (!in_array($v[0], $this->versions)) {
            throw new \Exception("Version {$this->app->version()} not supported");
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
