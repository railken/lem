<?php

namespace Railken\Lem\Exceptions;

class ModelNotAuthorizedException extends ModelException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = '%s_NOT_AUTHORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized, missing %s permission";
}
