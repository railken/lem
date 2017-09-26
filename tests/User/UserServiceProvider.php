<?php

namespace Railken\Laravel\Manager\Tests\User;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        UserManager::repository(UserRepository::class);
        UserManager::parameters(UserParameterBag::class);
        UserManager::validator(UserValidator::class);
        UserManager::authorizer(UserAuthorizer::class);
        UserManager::serializer(UserSerializer::class);

        User::observe(UserObserver::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
