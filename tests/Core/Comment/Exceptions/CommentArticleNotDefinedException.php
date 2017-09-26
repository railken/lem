<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

class CommentArticleNotDefinedException extends CommentAttributeException
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
    protected $code = 'COMMENT_ARTICLE_NOT_DEFINED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is required";
}
