<?php

namespace Railken\Laravel\Manager;

use Railken\Bag;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;

abstract class ParameterBag extends Bag implements ParameterBagContract
{
    /**
     * Filter current bag using agent
     *
     * @param AgentContract $agent
     *
     * @return $this
     */
    public function filterWrite(AgentContract $agent)
    {
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
        return $this;
    }
}
