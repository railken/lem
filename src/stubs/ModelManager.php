<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Permission\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

use $NAMESPACE$\$NAME$;

class $NAME$Manager extends ModelManager
{

	/**
	 * Construct
	 */
	public function __construct(AgentContract $agent = null)
	{
		$this->repository = new $NAME$Repository($this);
		$this->authorizer = new $NAME$Authorizer($this);
		$this->validator = new $NAME$Validator($this);
		$this->serializer = new $NAME$Serializer($this);

		parent::__construct($agent);
	}

	/**
	 * Fill the entity
	 *
	 * @param EntityContract $entity
	 * @param $NAME$ParameterBag $parameters
	 *
	 * @return EntityContract
	 */
	public function fill(EntityContract $entity, ParameterBag $parameters)
	{
		$parameters = $parameters->only(['name']);

		$entity->fill($parameters);

		return $entity;
	}
}
