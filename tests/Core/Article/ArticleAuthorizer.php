<?php

namespace Railken\Lem\Tests\Core\Article;

use Railken\Lem\Authorizer;
use Railken\Lem\Tokens;

class ArticleAuthorizer extends Authorizer
{
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'article.create',
        Tokens::PERMISSION_UPDATE => 'article.update',
        Tokens::PERMISSION_SHOW   => 'article.show',
        Tokens::PERMISSION_REMOVE => 'article.remove',
    ];
}
