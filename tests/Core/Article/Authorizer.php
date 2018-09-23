<?php

namespace Railken\Lem\Tests\Core\Article;

use Railken\Lem\Authorizer as BaseAuthorizer;
use Railken\Lem\Tokens;

class Authorizer extends BaseAuthorizer
{
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'article.create',
        Tokens::PERMISSION_UPDATE => 'article.update',
        Tokens::PERMISSION_SHOW   => 'article.show',
        Tokens::PERMISSION_REMOVE => 'article.remove',
    ];
}
