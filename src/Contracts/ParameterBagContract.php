<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\Contracts\AgentContract;

interface ParameterBagContract
{
        
        
    /**
     * Filter current bag using agent
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterByAgent(AgentContract $agent);
}
