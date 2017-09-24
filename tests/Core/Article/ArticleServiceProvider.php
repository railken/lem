<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Gate;
use Illuminate\Support\ServiceProvider;

class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Article::observe(ArticleObserver::class);
        Gate::policy(Article::class, ArticlePolicy::class);
    }
}
