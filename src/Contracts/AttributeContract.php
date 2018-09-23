<?php

namespace Railken\Lem\Contracts;

use Railken\Bag;

interface AttributeContract
{
    /**
     * Validate.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function validate(EntityContract $entity, Bag $parameters);
}
