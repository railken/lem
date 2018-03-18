<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo;

use Railken\Laravel\Manager\ModelSerializer;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tokens;
use Railken\Bag;

class FooSerializer extends ModelSerializer
{

	/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 * @param Collection $select
	 *
	 * @return array
	 */
	public function serialize(EntityContract $entity, Collection $select = null)
	{

        $bag = new Bag($entity->toArray());

        if ($select)
        	$bag = $bag->only($select->toArray());


        $bag = $bag->only($this->getManager()->authorizer->getAuthorizedAttributes(Tokens::PERMISSION_SHOW, $entity)->keys()->toArray());

		return $bag;
	}

}
