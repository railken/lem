<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelAuthorizer;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\Core\Article\Exceptions as Exceptions;
use Respect\Validation\Validator as v;
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
