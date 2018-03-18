<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\ParameterBag;

interface ModelValidatorContract
{
    /**
     * Validate.
     *
     * @param EntityContract $entity
     * @param ParameterBag   $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters);
}
