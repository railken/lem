<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\Contracts\AgentContract;

interface ModelPolicyContract
{

    /**
     * Determine if the given entity can be updated by the agent.
     *
     * @param AgentContract $agent
     * @param EntityContract $entity
     *
     * @return bool
     */
    public function update(AgentContract $agent, EntityContract $entity);
}
