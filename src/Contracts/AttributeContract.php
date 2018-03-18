<?php

namespace Railken\Laravel\Manager\Contracts;

interface AttributeContract
{
    /**
     * Validate.
     *
     * @param EntityContract       $entity
     * @param ParameterBagContract $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function validate(EntityContract $entity, ParameterBagContract $parameters);
}
