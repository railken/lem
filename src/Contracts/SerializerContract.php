<?php

namespace Railken\Lem\Contracts;

use Illuminate\Support\Collection;

interface SerializerContract
{
    /**
     * Serialize.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Collection                            $select
     *
     * @return \Railken\Bag
     */
    public function serialize(EntityContract $entity, Collection $select = null);
}
