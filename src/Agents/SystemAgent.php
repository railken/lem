<?php

namespace Railken\Lem\Agents;

use Railken\Lem\Contracts\AgentContract;

class SystemAgent implements AgentContract
{
    public $id = 0;

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
