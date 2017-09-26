<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class ArticleParameterBag extends ParameterBag
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
			$this->set('author', $agent);
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
		$this->filter(['id', 'title', 'description', 'created_at', 'updated_at', 'author_id']);

		if ($agent instanceof UserAgentContract || $agent instanceof GuestAgentContract) {
            return $this;
        }

        if ($agent instanceof SystemAgentContract) {
            return $this;
        }
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

		$this->filter(['title', 'description', 'author']);

		if ($agent instanceof UserAgentContract) {
			return $this->set('author', $agent);
		}

		if ($agent instanceof GuestAgentContract) {
			return $this->only(['title', 'description']);
		}

		if ($agent instanceof SystemAgentContract) {
			return $this;
		}
	}


}
