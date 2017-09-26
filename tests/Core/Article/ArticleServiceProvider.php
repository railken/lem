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
        ArticleManager::repository(ArticleRepository::class);
        ArticleManager::parameters(ArticleParameterBag::class);
        ArticleManager::validator(ArticleValidator::class);
        ArticleManager::authorizer(ArticleAuthorizer::class);
        ArticleManager::serializer(ArticleSerializer::class);

        Article::observe(ArticleObserver::class);
        Gate::policy(Article::class, ArticlePolicy::class);
    }
}
