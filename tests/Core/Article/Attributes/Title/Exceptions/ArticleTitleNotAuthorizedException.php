<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\Title\Exceptions;

use Railken\Laravel\Manager\Tests\Core\Article\Exceptions\ArticleAttributeException;

class ArticleTitleNotAuthorizedException extends ArticleAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'title';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'ARTICLE_TITLE_NOT_AUTHTORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
