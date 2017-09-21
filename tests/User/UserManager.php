<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelContract;
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
	 * @param ModelContract $entity
	 * @param ParameterBag $parameters
	 *
	 * @return ModelContract
	 */
	public function fill(ModelContract $entity, ParameterBag $parameters)
	{

		$parameters = $parameters->only(['username', 'role', 'password', 'email']);


		$entity->fill($parameters->all());

		return $entity;

	}

	/**
	 * This will prevent from saving entity with null value
	 *
	 * @param ModelContract $entity
	 *
	 * @return ModelContract
	 */
	public function save(ModelContract $entity)
	{
		return parent::save($entity);
	}

    /**
     * Remove a ModelContract
     *
     * @param Railken\Laravel\Manager\ModelContract $entity
     *
     * @return void
     */
    public function remove(ModelContract $entity)
    {
        return $entity->delete();
    }

}
