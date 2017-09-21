<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Permission\AgentContract;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Tests\User\User;
use Illuminate\Support\Collection;

class UserManager extends ModelManager
{

    /**
     * Construct
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
     * @param array|ParameterBag $parameters
     *
     * @return ParameterBag
     */
    public function parameters($parameters)
    {
        return new UserParameterBag($parameters);
    }
    

    /**
     * Fill the entity
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return EntityContract
     */
    public function fill(EntityContract $entity, ParameterBag $parameters)
    {
        $parameters = $parameters->only(['username', 'role', 'password', 'email']);
        
        $entity->fill($parameters->all());

        return $entity;
    }
}
