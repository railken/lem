<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Illuminate\Support\ServiceProvider;

class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        Article::observe(ArticleObserver::class);
    }
}
