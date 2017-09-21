<?php

namespace Railken\Laravel\Manager;

use Railken\Bag;
use Railken\Laravel\Manager\Permission\AgentContract;

class ParameterBag extends Bag implements ParameterBagContract
{
	
	/**
	 * Filter current bag using agent
	 *
	 * @param AgentContract $agent
	 *
	 * @return this
	 */
	public function filterByAgent(AgentContract $agent)
    {  
        return $this;
    }
}