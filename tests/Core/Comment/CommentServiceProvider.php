<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Gate;
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
        Comment::observe(CommentObserver::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
