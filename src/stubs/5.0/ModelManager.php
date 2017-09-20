<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\ModelContract;
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
	 * @param ModelContract $entity
	 * @param array $parameters
	 *
	 * @return ModelContract
	 */
	public function fill(ModelContract $entity, array $parameters)
	{

		$parameters = $this->getOnlyParams($parameters, ['name']);

		$entity->fill($parameters);

		return $entity;

	}

	/**
	 * This will prevent from saving entity with null value
	 *
	 * @param ModelContract $entity
	 *
	 * @return ModelContract
	 */
	public function save(ModelContract $entity)
	{
		$this->throwExceptionParamsNull([
			'name' => $entity->name,
		]);

		return parent::save($entity);
	}


}
