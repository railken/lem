<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\Password\Exceptions;

use Railken\Laravel\Manager\Tests\User\Exceptions\UserAttributeException;

class UserPasswordNotAuthorizedException extends UserAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'password';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'USER_PASSWORD_NOT_AUTHTORIZED';
    
    /**
     * The message
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
