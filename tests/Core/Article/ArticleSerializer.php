<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\ModelSerializer;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Bag;

class ArticleSerializer extends ModelSerializer
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

        // ? $this->authorize('show', $entity, null)

		return $bag;
	}

}
