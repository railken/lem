<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelSerializer;
use Railken\Laravel\Manager\ModelContract;

use Railken\Bag;

class UserSerializer extends ModelSerializer
{

	/**
	 * Serialize entity
	 *
	 * @param ModelContract $entity
	 *
	 * @return array
	 */
	public function serialize(ModelContract $entity)
	{
		$bag = new Bag();

		$bag->set('id', $entity->id);
		$bag->set('email', $entity->email);
		$bag->set('username', $entity->username);

		return $bag->all();
	}

}
