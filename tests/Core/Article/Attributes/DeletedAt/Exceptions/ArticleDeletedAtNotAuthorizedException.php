<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\DeletedAt\Exceptions;

use Railken\Laravel\Manager\Tests\Core\Article\Exceptions\ArticleAttributeException;

class ArticleDeletedAtNotAuthorizedException extends ArticleAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'deleted_at';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'ARTICLE_DELETED_AT_NOT_AUTHTORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
