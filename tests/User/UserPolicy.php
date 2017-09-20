<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Permission\AgentContract;

class UserPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param AgentContract $agent
     * @param User $user
     *
     * @return bool
     */
    public function update(AgentContract $agent, User $user)
    {   
        return $agent->isRoleAdmin() || ($agent->isRoleUser() && $agent->id == $user->id);
    }
}