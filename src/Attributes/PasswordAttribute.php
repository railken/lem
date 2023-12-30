<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;
use Respect\Validation\Validator as v;

class PasswordAttribute extends TextAttribute
{
    /**
     * Name of the attribute.
     *
     * @var string
     */
    protected $name = 'password';

    /**
     * MinLength.
     *
     * @var int
     */
    protected $minLength = 8;

    /**
     * Is the attribute hidden.
     *
     * @var bool
     */
    protected $hidden = true;

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
        return v::length($this->getMinLength(), $this->getMaxLength())->validate($value);
    }

    /**
     * Is readable
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return false;
    }
}
