<?php

namespace Railken\Lem\Exceptions;

class AttributeNotMutableException extends AttributeException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = '%s_%s_NOT_MUTABLE';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The value cannot be changed';
}
