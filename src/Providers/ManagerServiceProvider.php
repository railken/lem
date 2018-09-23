<?php

namespace Railken\Lem\Providers;

use Illuminate\Support\ServiceProvider;
use Railken\Lem\Console\Commands\GenerateCommand;

class ManagerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->commands([
            GenerateCommand::class,
        ]);
    }
}