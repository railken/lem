<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Bag;

interface ModelValidatorContract
{
    /**
     * Validate.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     * @param Bag                                               $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function validate(EntityContract $entity, Bag $parameters);
}
