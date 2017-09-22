<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ModelPolicyContract;
use Railken\Laravel\Manager\Contracts\EntityContract;

class $NAME$Policy implements ModelPolicyContract
{

    /**
     * Determine if the given entity can be manipulated by the agent.
     *
     * @param AgentContract $agent
     *
     * @return bool
     */
    public function interact(AgentContract $agent, EntityContract $entity = null)
    {
        return true;
    }

    /**
     * Determine if the agent can create an entity
     *
     * @param AgentContract $agent
     *
     * @return bool
     */
    public function create(AgentContract $agent)
    {
        return true;
    }

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
    	return $this->interact($agent, $entity);
    }

    /**
     * Determine if the given entity can be retrieved by the agent.
     *
     * @param AgentContract $agent
     * @param EntityContract $entity
     *
     * @return bool
     */
    public function retrieve(AgentContract $agent, EntityContract $entity)
    {
        return $this->interact($agent, $entity);
    }

    /**
     * Determine if the given entity can be removed by the agent.
     *
     * @param AgentContract $agent
     * @param EntityContract $entity
     *
     * @return bool
     */
    public function remove(AgentContract $agent, EntityContract $entity)
    {
        return $this->interact($agent, $entity);
    }
}
