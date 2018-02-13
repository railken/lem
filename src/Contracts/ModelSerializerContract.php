<?php

namespace Railken\Laravel\Manager\Contracts;

use Illuminate\Support\Collection;

interface ModelSerializerContract
{

    /**
     * Serialize
     *
     * @param EntityContract $entity
     *
     * @return Collection
     */
    public function serialize(EntityContract $entity, Collection $select);

}