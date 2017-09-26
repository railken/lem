<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

class CommentArticleNotValidException extends CommentAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'article';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'COMMENT_ARTICLE_NOT_VALID';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not valid";
}
