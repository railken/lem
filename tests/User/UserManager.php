<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

class UserManager extends ModelManager
{

    /**
     * Construct
     *
     * @param AgentContract|null $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->repository = new UserRepository($this);
        $this->serializer = new UserSerializer($this);
        $this->validator = new UserValidator($this);
        $this->authorizer = new UserAuthorizer($this);

        parent::__construct($agent);
    }

    /**
     * Filter parameters
     *
     * @param ParameterBag|array $parameters
     *
     * @return ParameterBag
     */
    public function parameters($parameters)
    {
        return new UserParameterBag($parameters);
    }
}
