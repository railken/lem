<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelSerializer;
use Railken\Laravel\Manager\EntityContract;

use Railken\Bag;

class UserSerializer extends ModelSerializer
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
		$bag = new Bag();

		$bag->set('id', $entity->id);
		$bag->set('email', $entity->email);
		$bag->set('username', $entity->username);

		return $bag->all();
	}

}
