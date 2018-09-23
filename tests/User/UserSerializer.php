<?php

namespace Railken\Lem\Tests\User;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Serializer;

class UserSerializer extends Serializer
{
    /**
     * Serialize entity.
     *
     * @param EntityContract $entity
     * @param Collection     $select
     *
     * @return array
     */
    public function serialize(EntityContract $entity, Collection $select = null)
    {
        $bag = (new Bag($entity->toArray()))->only($select->toArray());

        return $bag;
    }
}
