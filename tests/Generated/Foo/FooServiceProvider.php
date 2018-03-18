<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo;

use Illuminate\Support\ServiceProvider;

class FooServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Foo::observe(FooObserver::class);
    }
}
