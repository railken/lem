<?php

namespace Railken\Lem\Exceptions;

class AttributeNotAuthorizedException extends AttributeException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = '%s_%s_NOT_AUTHORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized, missing %s permission";
}
