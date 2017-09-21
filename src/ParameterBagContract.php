<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Permission\AgentContract;

interface ParameterBagContract
{
		
		
	/**
	 * Filter current bag using agent
	 *
	 * @param AgentContract $agent
	 *
	 * @return this
	 */
	public function filterByAgent(AgentContract $agent);

}