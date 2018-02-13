<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Bag;
use Illuminate\Support\Collection;

class ArticleSerializer implements ModelSerializerContract
{

    /**
     * Serialize entity
     *
     * @param EntityContract $entity
     * @param Collection $select
     *
     * @return array
     */
    public function serialize(EntityContract $entity, Collection $select)
    {

        $bag = (new Bag($entity->toArray()))->only($select->toArray());

        return $bag;
    }
}
