<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class $NAME$ParameterBag extends ParameterBag
{

	/**
	 * Filter current bag using agent
	 *
	 * @param AgentContract $agent
	 *
	 * @return $this
	 */
	public function filterByAgent(AgentContract $agent)
	{
		if ($agent instanceof UserAgentContract) {
			return $this;
        }

        if ($agent instanceof GuestAgentContract) {
            return $this;
        }

        if ($agent instanceof SystemAgentContract) {
            return $this;
        }
	}

	/**
	 * Filter current bag using agent for a search
	 *
	 * @param AgentContract $agent
	 *
	 * @return $this
	 */
	public function filterSearchableByAgent(AgentContract $agent)
	{
		if ($agent instanceof UserAgentContract) {
			return $this;
        }

        if ($agent instanceof GuestAgentContract) {
            return $this;
        }

        if ($agent instanceof SystemAgentContract) {
            return $this;
        }
	}

	/**
	 * Filter current bag to fill model
	 *
	 * @return $this
	 */
	public function filterFill()
	{
		return $this->only(['name']);
	}

}
