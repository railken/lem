<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;

class EmailAttribute extends TextAttribute
{
    /**
     * Name of the attribute.
     *
     * @var string
     */
    protected $name = 'email';

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
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
