<?php

namespace $NAMESPACE$

use Railken\Laravel\Manager\Permission\AgentContract;
use Railken\Laravel\Manager\Contracts\ModelPolicyContract;

class $NAME$Policy implements ModelPolicyContract
{
    /**
     * Determine if the given entity can be updated by the agent.
     *
     * @param AgentContract $agent
     * @param EntityContract $entity
     *
     * @return bool
     */
    public function update(AgentContract $agent, EntityContract $entity)
    {   
    	// ...
    }
}