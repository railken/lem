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
        if ($agent instanceof UserAgentContract) {
            $this->set('author', $agent);
        }

        if ($agent instanceof SystemAgentContract) {
            $am = new \Railken\Laravel\Manager\Tests\User\UserManager();
            $this->exists('author_id') && $this->set('author', $am->findOneBy(['id' => $this->get('author_id')]));
        }

        $this->filter(['title', 'description', 'author']);

        if ($agent instanceof UserAgentContract) {
            return $this;
        }

        if ($agent instanceof SystemAgentContract) {
            return $this;
        }

        # GuestAgentContract not allowed.
    }
}
