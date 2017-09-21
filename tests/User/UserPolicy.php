<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Permission\AgentContract;
use Railken\Laravel\Manager\ModelPolicyContract;
use Railken\Laravel\Manager\EntityContract;

class UserPolicy implements ModelPolicyContract
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param AgentContract $agent
     * @param EntityContract $entity
     *
     * @return bool
     */
    public function update(AgentContract $agent, EntityContract $entity)
    {
        return $agent->isRoleAdmin() || ($agent->isRoleUser() && $agent->id == $entity->id);
    }
}
