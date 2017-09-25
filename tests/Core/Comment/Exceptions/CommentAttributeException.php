<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

abstract class CommentAttributeException extends CommentException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute;

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'COMMENT_ATTRIBUTE_NOT_VALID';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is invalid";

    /**
     * Construct
     *
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        $this->label = $this->attribute;

        return parent::__construct($value);
    }
}
