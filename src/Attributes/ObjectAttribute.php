<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;

class ObjectAttribute extends TextAttribute
{
    /**
     * Is a value valid ?
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param mixed                                 $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return is_object($value) || is_array($value);
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
        return (object) [];
    }
}
