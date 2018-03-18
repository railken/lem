<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\AuthorId\Exceptions;

use Railken\Laravel\Manager\Tests\Core\Article\Exceptions\ArticleAttributeException;

class ArticleAuthorIdNotValidException extends ArticleAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'author_id';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'ARTICLE_AUTHOR_ID_NOT_VALID';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is not valid';
}
