<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

class CommentAuthorNotDefinedException extends CommentAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'author';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'COMMENT_AUTHOR_NOT_DEFINED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is required";
}
