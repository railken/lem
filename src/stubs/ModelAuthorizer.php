<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;
use Illuminate\Support\Collection;
use $NAMESPACE$\Exceptions as Exceptions;

class $NAME$Authorizer implements ModelAuthorizerContract
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

		!$this->manager->agent->can('update', $entity) && $errors->push(new Exceptions\$NAME$NotAuthorizedException($entity));

		return $errors;
	}

}
