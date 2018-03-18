<?php

namespace Railken\Laravel\Manager\Tests\User\Exceptions;

use Exception;
use Railken\Laravel\Manager\Exceptions\ModelNotAuthorizedExceptionContract;

class UserNotAuthorizedException extends Exception implements ModelNotAuthorizedExceptionContract
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'USER_NOT_AUTHORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized";
}
