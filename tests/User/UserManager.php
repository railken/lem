<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

class UserManager extends ModelManager
{

    /**
     * @var array
     */
    protected static $__components = [];

    /**
     * Construct
     *
     * @param AgentContract|null $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        parent::__construct($agent);
    }
}
