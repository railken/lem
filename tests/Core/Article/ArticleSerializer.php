<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Bag;

class ArticleSerializer implements ModelSerializerContract
{

	/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 *
	 * @return array
	 */
	public function serialize(EntityContract $entity)
	{
		$bag = $this->serializeBrief($entity);

		return $bag;
	}

	/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 *
	 * @return array
	 */
	public function serializeBrief(EntityContract $entity)
	{
		$bag = new Bag();

		$bag->set('id', $entity->id);
		$bag->set('title', $entity->title);
		$bag->set('description', $entity->description);
		$bag->set('author_id', $entity->author->id);

		return $bag;
	}
}
