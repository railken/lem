<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\Username;

use Railken\Laravel\Manager\Attributes\BaseAttribute;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Tokens;

class UsernameAttribute extends BaseAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'username';

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model.
     *
     * @var bool
     */
    protected $required = true;

    /**
     * Is the attribute unique.
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * List of all exceptions used in validation.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_DEFINED    => Exceptions\UserUsernameNotDefinedException::class,
        Tokens::NOT_VALID      => Exceptions\UserUsernameNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\UserUsernameNotAuthorizedException::class,
    ];

    /**
     * List of all permissions.
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'user.attributes.username.fill',
        Tokens::PERMISSION_SHOW => 'user.attributes.username.show',
    ];

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return strlen($value) >= 3 && strlen($value) < 32;
    }
}
