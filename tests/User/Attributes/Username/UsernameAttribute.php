<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\Username;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelAttribute;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Railken\Laravel\Manager\Tests\User\Attributes\Username\Exceptions as Exceptions;
use Railken\Laravel\Manager\Tokens;
use Respect\Validation\Validator as v;

class UsernameAttribute extends ModelAttribute
{

    /**
     * Name attribute
     *
     * @var string
     */
    protected $name = 'username';

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model
     *
     * @var boolean
     */
    protected $required = true;

    /**
     * Is the attribute unique
     *
     * @var boolean
     */
    protected $unique = false;

    /**
     * List of all exceptions used in validation
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_DEFINED => Exceptions\UserUsernameNotDefinedException::class,
        Tokens::NOT_VALID => Exceptions\UserUsernameNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\UserUsernameNotAuthorizedException::class
    ];

    /**
     * List of all permissions
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'user.attributes.username.fill',
        Tokens::PERMISSION_SHOW => 'user.attributes.username.show'
    ];

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed $value
     *
     * @return boolean
     */
    public function valid(EntityContract $entity, $value)
    {
        return strlen($value) >= 3 && strlen($value) < 32;
    }
}
