<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Exceptions;

use Railken\Laravel\Manager\Exceptions\ModelNotAuthorizedExceptionContract;

class ArticleNotAuthorizedException extends ArticleException implements ModelNotAuthorizedExceptionContract
{

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'ARTICLE_NOT_AUTHORIZED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "You're not authorized";
}
