<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Railken\Bag;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class CommentParameterBag extends ParameterBag
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


        $this->exists('author_id') && $this->set('author', $manager->author->findOneBy(['id' => $this->get('author_id')]));
        $this->exists('article_id') && $this->set('article', $manager->article->findOneBy(['id' => $this->get('article_id')]));

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
        $this->filter(['id', 'content', 'author_id', 'article_id', 'created_at', 'updated_at']);

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
     * Filter current bag using agent
     *
     * @param AgentContract $agent
     *
     * @return $this
     */
    public function filterWrite(AgentContract $agent)
    {

        $this->filter(['content', 'author', 'article']);

        if ($agent instanceof UserAgentContract) {
            return $this->set('author', $agent);
        }

        if ($agent instanceof SystemAgentContract) {
            return $this;
        }
    }
}
