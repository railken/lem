<?php

namespace Railken\Laravel\Manager\Agents;

use Railken\Laravel\Manager\Contracts\AgentContract;

class SystemAgent implements AgentContract
{
    /**
     * Has permission.
     *
     * @param string $permission
     * @param array  $arguments
     *
     * @return bool
     */
    public function can($permission, $arguments = [])
    {
        return true;
    }
}
