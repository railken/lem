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
		parent::__construct($agent);
	}

}
