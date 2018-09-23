<?php

namespace Railken\Lem\Contracts;

interface AgentContract
{
    /**
     * Determine if the entity has a given ability.
     *
     * @param string      $ability
     * @param array|mixed $arguments
     *
     * @return bool
     */
    public function can($ability, $arguments = []);
}
