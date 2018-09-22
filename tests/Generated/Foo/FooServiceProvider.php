<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo;

use Illuminate\Support\ServiceProvider;

class FooServiceProvider extends ServiceProvider
{   

	/**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Foo::observe(FooObserver::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    	// ...
    }
}
