<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

class $NAME$Manager extends ModelManager
{

	/**
	 * Construct
	 *
	 * @param AgentContract|null $agent
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
	 * Filter parameters
	 *
	 * @param array|ParameterBag $parameters
	 *
	 * @return ParameterBag
	 */
	public function parameters($parameters)
	{
		return new $NAME$ParameterBag($parameters);
	}
}
