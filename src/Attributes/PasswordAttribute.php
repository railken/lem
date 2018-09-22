<?php

namespace Railken\Laravel\Manager\Attributes;

use Railken\Laravel\Manager\Contracts\EntityContract;
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
}
