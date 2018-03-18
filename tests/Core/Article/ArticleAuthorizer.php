<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\ModelAuthorizer;
use Railken\Laravel\Manager\Tokens;

class ArticleAuthorizer extends ModelAuthorizer
{
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'article.create',
        Tokens::PERMISSION_UPDATE => 'article.update',
        Tokens::PERMISSION_SHOW => 'article.show',
        Tokens::PERMISSION_REMOVE => 'article.remove',
    ];
}
