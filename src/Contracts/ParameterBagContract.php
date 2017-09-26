<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;

interface ParameterBagContract
{
    /**
     * Filter current bag using agent
     *
     * @param ManagerContract $manager
     * @param AgentContract $agent
     *
     * @return this
     */
    public function parse(ManagerContract $manager, AgentContract $agent);

    /**
     * Filter current bag using agent
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterWrite(AgentContract $agent);

    /**
     * Filter current bag using agent for a search
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterRead(AgentContract $agent);

}
