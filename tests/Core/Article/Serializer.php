<?php

namespace Railken\Lem\Tests\Core\Article;

use Illuminate\Support\Collection;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Serializer as BaseSerializer;

class Serializer extends BaseSerializer
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
        $bag = parent::serialize($entity, $select);

        // ...

        return $bag;
    }
}
