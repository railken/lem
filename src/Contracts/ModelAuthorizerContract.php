<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\ParameterBag;


interface ModelAuthorizerContract
{

    /**
     * Authorize update
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, ParameterBag $parameters);
}
