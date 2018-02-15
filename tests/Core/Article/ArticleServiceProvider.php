<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

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

        ArticleManager::repository(ArticleRepository::class);
        ArticleManager::serializer(ArticleSerializer::class);
        ArticleManager::parameters(ArticleParameterBag::class);
        ArticleManager::validator(ArticleValidator::class);
        ArticleManager::authorizer(ArticleAuthorizer::class);
    }
}
