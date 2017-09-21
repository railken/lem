<?php

namespace Railken\Laravel\Manager\Tests\User;

use Gate;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {   
        User::observe(UserObserver::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
