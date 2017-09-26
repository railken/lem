<?php

namespace $NAMESPACE$;

use Gate;
use Illuminate\Support\ServiceProvider;

class $NAME$ServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $NAME$::observe($NAME$Observer::class);
        Gate::policy($NAME$::class, $NAME$Policy::class);

        $NAME$Manager::repository($NAME$Repository::class);
        $NAME$Manager::serializer($NAME$Serializer::class);
        $NAME$Manager::parameters($NAME$ParameterBag::class);
        $NAME$Manager::validator($NAME$Validator::class);
        $NAME$Manager::authorizer($NAME$Authorizer::class);
    }
}
