<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\Id;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelAttribute;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Railken\Laravel\Manager\Tests\User\Attributes\Id\Exceptions as Exceptions;
use Railken\Laravel\Manager\Tokens;
use Respect\Validation\Validator as v;

class IdAttribute extends ModelAttribute
{

    /**
     * Name attribute
     *
     * @var string
     */
    protected $name = 'id';

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model
     *
     * @var boolean
     */
    protected $required = false;

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
        Tokens::NOT_DEFINED => Exceptions\UserIdNotDefinedException::class,
        Tokens::NOT_VALID => Exceptions\UserIdNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\UserIdNotAuthorizedException::class
    ];

    /**
     * List of all permissions
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'user.attributes.id.fill',
        Tokens::PERMISSION_SHOW => 'user.attributes.id.show'
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
        return v::length(1, 255)->validate($value);
    }
}
