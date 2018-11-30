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
        return 1 == intval($value) || 0 == intval($value);
    }

    /**
     * Retrieve default value.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return mixed
     */
    public function getDefault(EntityContract $entity)
    {
        return 0;
    }
}
