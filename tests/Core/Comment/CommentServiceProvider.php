<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        CommentManager::repository(CommentRepository::class);
        CommentManager::parameters(CommentParameterBag::class);
        CommentManager::validator(CommentValidator::class);
        CommentManager::authorizer(CommentAuthorizer::class);
        CommentManager::serializer(CommentSerializer::class);

        Comment::observe(CommentObserver::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
