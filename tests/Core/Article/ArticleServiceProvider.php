<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Illuminate\Support\Facades\Gate;
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
        ArticleManager::repository(ArticleRepository::class);
        ArticleManager::parameters(ArticleParameterBag::class);
        ArticleManager::validator(ArticleValidator::class);
        ArticleManager::serializer(ArticleSerializer::class);
        ArticleManager::authorizer(ArticleAuthorizer::class);

        Article::observe(ArticleObserver::class);
    }
}
