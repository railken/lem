<?php

namespace Railken\Laravel\Manager\Exceptions;

class AttributeNotAuthorizedException extends AttributeException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = '%s_NOT_AUTHTORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
