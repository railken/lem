<?php

namespace Railken\Lem\Contracts;

use Railken\Bag;

interface ValidatorContract
{
    /**
     * Validate.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Bag                                   $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function validate(EntityContract $entity, Bag $parameters);
}
