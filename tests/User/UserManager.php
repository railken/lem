<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Tokens;


class UserManager extends ModelManager
{

    /**
     * @var array
     */
    protected static $__components = [];

    /**
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\UserNotAuthorizedException::class
    ];

    /**
     * Construct
     *
     * @param AgentContract $agent
     *
     */
    public function __construct(AgentContract $agent = null)
    {
        parent::__construct($agent);
    }
}
