<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ModelPolicyContract;
use Railken\Laravel\Manager\Contracts\EntityContract;

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
