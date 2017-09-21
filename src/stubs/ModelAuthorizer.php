<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Tests\User\Exceptions as Exceptions;
use Railken\Laravel\Manager\Exceptions\NotAuthorizedException;
use Illuminate\Support\Collection;

class $NAME$Authorizer
{

	/**
	 * @var $NAME$Manager
	 */
	protected $manager;

	/**
	 * Construct
	 */
	public function __construct($NAME$Manager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * Authorize
	 *
	 * @param EntityContract $entity
	 * @param ParameterBag $parameters
	 *
	 * @return Collection
	 */
	public function update(EntityContract $entity, ParameterBag $parameters)
	{
		$errors = new Collection();

		!$this->manager->agent->can('update', $entity) && $errors->push(new NotAuthorizedException($entity));

		return $errors;
	}

}
