<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Permission\AgentContract;

use $NAMESPACE$\$NAME$;

class $NAME$Manager extends ModelManager
{

	/**
	 * Construct
	 */
	public function __construct(AgentContract $agent = null)
	{
		$this->repository = new $NAME$Repository($this);
		$this->serializer = new $NAME$Serializer($this);

		parent::__construct($agent);
	}

	/**
	 * Fill the entity
	 *
	 * @param EntityContract $entity
	 * @param array $parameters
	 *
	 * @return EntityContract
	 */
	public function fill(EntityContract $entity, array $parameters)
	{

		$parameters = $this->getOnlyParams($parameters, ['name']);

		$entity->fill($parameters);

		return $entity;

	}

	/**
	 * This will prevent from saving entity with null value
	 *
	 * @param EntityContract $entity
	 *
	 * @return EntityContract
	 */
	public function save(EntityContract $entity)
	{
		$this->throwExceptionParamsNull([
			'name' => $entity->name,
		]);

		return parent::save($entity);
	}


}
