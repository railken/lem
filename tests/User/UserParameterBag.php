<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

class UserParameterBag extends ParameterBag
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
        if ($agent->isRoleUser()) {
            return $this->only(['username', 'email', 'password']);
        }

        if ($agent->isRoleAdmin()) {
            return $this;
        }
    }

    /**
     * Filter current bag using agent for a search
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterSearchableByAgent(AgentContract $agent)
    {
        if ($agent->isRoleUser()) {
            return $this->only(['username', 'email']);
        }

        if ($agent->isRoleAdmin()) {
            return $this->only(['username', 'email']);
        }
    }

	/**
	 * Filter current bag to fill model
	 *
	 * @return $this
	 */
	public function filterFill()
	{
		return $this->only(['username', 'role', 'password', 'email']);
	}

}
