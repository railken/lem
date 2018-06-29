<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Bag;

interface ModelAuthorizerContract
{
    /**
     * Validate.
     *
     * @param string         $action
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     * @param Bag   $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function authorize(string $action, EntityContract $entity, Bag $parameters);


    public function getAuthorizedAttributes(string $action, EntityContract $entity);
}
