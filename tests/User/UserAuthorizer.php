<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelContract;
use Railken\Bag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\User\Exceptions as Exceptions;

class UserAuthorizer
{

	/**
	 * @var ModelManager
	 */
	protected $manager;

	/**
	 * Construct
	 */
	public function __construct(UserManager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * Authorize
	 *
	 * @param ModelContract $entity
	 * @param Bag $params
	 *
	 * @return Collection
	 */
	public function filter(ModelContract $entity, Bag $params)
	{	

		if (!$this->manager->agent)
			return $params;

		if ($this->manager->agent->isRoleAdmin())
			return $params;

		if ($this->manager->agent->isRoleUser())
			return $params->only(['username', 'email', 'password']);

	}

	/**
	 * Authorize
	 *
	 * @param ModelContract $entity
	 * @param Bag $params
	 *
	 * @return Collection
	 */
	public function authorize(ModelContract $entity, Bag $params)
	{
		$errors = new Collection();

		!$this->manager->agent->can('update', $entity) && $errors->push(new \Railken\Laravel\Manager\Exceptions\NotAuthorizedException($entity));

		return $errors;
	}

}
