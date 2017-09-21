<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Permission\AgentContract;
use Railken\Laravel\Manager\EntityContract;

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