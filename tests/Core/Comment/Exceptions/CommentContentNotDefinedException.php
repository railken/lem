<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

class CommentContentNotDefinedException extends CommentAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'content';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'COMMENT_CONTENT_NOT_DEFINED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is required";
}
