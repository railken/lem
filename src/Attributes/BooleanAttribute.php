<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;

class BooleanAttribute extends NumberAttribute
{
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
        return 1 === $value || 0 === $value || true === $value || false === $value;
    }
}
