<?php

namespace Railken\Lem\Exceptions;

class AttributeNotValidException extends AttributeException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = '%s_NOT_VALID';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is not valid';
}
