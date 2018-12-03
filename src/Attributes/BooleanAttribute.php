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
     * Parse value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function parse($value)
    {
        return intval($value);
    }
}
