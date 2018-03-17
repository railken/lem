<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\ParameterBag;

interface ModelAuthorizerContract
{

    /**
     * Validate
     *
     * @param string $action
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function authorize(string $action, EntityContract $entity, ParameterBag $parameters);
}
