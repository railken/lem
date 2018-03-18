<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\UpdatedAt\Exceptions;

use Railken\Laravel\Manager\Tests\Core\Article\Exceptions\ArticleAttributeException;

class ArticleUpdatedAtNotAuthorizedException extends ArticleAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'updated_at';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'ARTICLE_UPDATED_AT_NOT_AUTHTORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
