<?php

namespace Railken\Lem\Contracts;

use Railken\Bag;

interface AuthorizerContract
{
    /**
     * Validate.
     *
     * @param string                                $action
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Bag                                   $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function authorize(string $action, EntityContract $entity, Bag $parameters);

    public function getAuthorizedAttributes(string $action, EntityContract $entity);
}
