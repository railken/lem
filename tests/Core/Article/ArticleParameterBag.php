<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class ArticleParameterBag extends ParameterBag
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
			return $this->only(['title', 'description'])->set('author', $agent);
        }

        if ($agent instanceof GuestAgentContract) {
            return $this->only(['title', 'description']);
        }

        if ($agent instanceof SystemAgentContract) {
            return $this->only(['title', 'description']);
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
			return $this->only(['title', 'description', 'created_at', 'updated_at']);
        }

        if ($agent instanceof GuestAgentContract) {
            return $this->only(['title', 'description', 'created_at', 'updated_at']);
        }

        if ($agent instanceof SystemAgentContract) {
            return $this->only(['title', 'description', 'created_at', 'updated_at']);
        }
	}

	/**
	 * Filter current bag to fill model
	 *
	 * @return $this
	 */
	public function filterFill()
	{
		return $this->only(['title', 'description', 'author']);
	}

}
