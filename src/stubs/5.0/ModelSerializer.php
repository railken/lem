<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\ModelSerializer;
use Railken\Laravel\Manager\EntityContract;

class $NAME$Serializer extends ModelSerializer
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
		return [
			'id' => $entity->id
		];
	}

}
