<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;

interface AttributeContract
{

	/**
     * Validate
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters);
}
