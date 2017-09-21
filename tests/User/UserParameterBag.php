<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Permission\AgentContract;
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
            return $this->only(['title']);
        }
 
        if ($agent->isRoleAdmin()) {
            return $this;
        }
    }
}
