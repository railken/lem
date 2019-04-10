<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;
use Ramsey\Uuid\Uuid;

class UuidAttribute extends TextAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'uuid';

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

        return $method !== null ? $method($entity, $this) : Uuid::uuid4();
    }
}
