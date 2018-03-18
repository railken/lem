<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\CreatedAt\Exceptions;

use Railken\Laravel\Manager\Tests\User\Exceptions\UserAttributeException;

class UserCreatedAtNotAuthorizedException extends UserAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'created_at';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'USER_CREATED_AT_NOT_AUTHTORIZED';
    
    /**
     * The message
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
