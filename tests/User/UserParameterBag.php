<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class UserParameterBag extends ParameterBag
{
    /**
	 * Filter current bag using agent for a search
	 *
	 * @param ManagerContract $manager
	 * @param AgentContract $agent
	 *
	 * @return $this
	 */
	public function parse(ManagerContract $manager, AgentContract $agent)
	{
		if ($agent instanceof UserAgentContract) {

		}

        return $this;
	}

    /**
     * Filter current bag using agent
     *
     * @param AgentContract $agent
     *
     * @return $this
     */
    public function filterWrite(AgentContract $agent)
    {
        $this->filter(['username', 'role', 'password', 'email']);

        // A user can change his own data.
        if ($agent instanceof UserAgentContract) {
            if ($agent->isRoleUser()) {
                return $this->only(['username', 'email', 'password']);
            }

            if ($agent->isRoleAdmin()) {
                return $this;
            }
        }

        // A guest can register.
        if ($agent instanceof GuestAgentContract) {
            return $this->only(['username', 'email', 'password']);
        }


        return $this;
    }

    /**
     * Filter current bag using agent for a search
     *
     * @param AgentContract $agent
     *
     * @return $this
     */
    public function filterRead(AgentContract $agent)
    {
        if ($agent instanceof UserAgentContract) {
            if ($agent->isRoleUser()) {
                return $this->only(['username', 'email']);
            }

            if ($agent->isRoleAdmin()) {
                return $this->only(['username', 'email']);
            }
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
    }
}
