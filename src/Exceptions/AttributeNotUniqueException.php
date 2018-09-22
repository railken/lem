<?php

namespace Railken\Laravel\Manager\Exceptions;

class AttributeNotUniqueException extends AttributeException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = '%s_NOT_UNIQUE';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is not unique';
}
