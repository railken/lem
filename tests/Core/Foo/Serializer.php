<?php

namespace Railken\Lem\Tests\Core\Foo;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Serializer as BaseSerializer;
use Railken\Lem\Tokens;

class Serializer extends BaseSerializer
{
    /**
     * Serialize entity.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Illuminate\Support\Collection $select
     *
     * @return \Railken\Bag
     */
    public function serialize(EntityContract $entity, Collection $select = null)
    {
        $bag = parent::serialize($entity, $select);

        // ...

        return $bag;
    }
}
