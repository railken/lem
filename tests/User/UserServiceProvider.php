<?php

namespace Railken\Lem\Tests\User;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        User::observe(UserObserver::class);
    }
}
