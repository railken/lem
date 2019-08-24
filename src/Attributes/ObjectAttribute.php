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
        return true;
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
        $method = $this->default;

        return $method !== null ? $method($entity, $this) : (object) [];
    }
}
