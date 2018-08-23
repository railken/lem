<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Bag;

interface AttributeContract
{
    /**
     * Validate.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     * @param \Railken\Bag                                      $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function validate(EntityContract $entity, Bag $parameters);
}
