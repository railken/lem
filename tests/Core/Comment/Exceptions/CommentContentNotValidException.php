<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

class CommentContentNotValidException extends CommentAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'name';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'COMMENT_CONTENT_NOT_VALID';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not valid";
}
