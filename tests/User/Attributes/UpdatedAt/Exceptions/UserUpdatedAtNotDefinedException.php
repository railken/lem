<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\UpdatedAt\Exceptions;

use Railken\Laravel\Manager\Tests\User\Exceptions\UserAttributeException;

class UserUpdatedAtNotDefinedException extends UserAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'updated_at';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'USER_UPDATED_AT_NOT_DEFINED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is required";
}
