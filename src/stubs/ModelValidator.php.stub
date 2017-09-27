<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use $NAMESPACE$\Exceptions as Exceptions;


class $NAME$Validator implements ModelValidatorContract
{

	/**
	 * @var ModelManager
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
	 * Validate
	 *
	 * @param EntityContract $entity
	 * @param ParameterBag $parameters
	 * @param bool $required
	 *
	 * @return Collection
	 */
	public function validate(EntityContract $entity, ParameterBag $parameters)
	{

		$errors = new Collection();

		if (!$entity->exists)
			$errors = $errors->merge($this->validateRequired($parameters));

		$errors = $errors->merge($this->validateValue($entity, $parameters));

		return $errors;
	}

	/**
	 * Validate "required" values
	 *
	 * @param EntityContract $entity
	 * @param ParameterBag $parameters
	 *
	 * @return Collection
	 */
	public function validateRequired(ParameterBag $parameters)
	{
		$errors = new Collection();

		!$parameters->exists('name') && $errors->push(new Exceptions\$NAME$NameNotDefinedException($parameters->get('name')));

		return $errors;
	}

	/**
	 * Validate "not valid" values
	 *
	 * @param ParameterBag $parameters
	 *
	 * @return Collection
	 */
	public function validateValue(EntityContract $entity, ParameterBag $parameters)
	{
		$errors = new Collection();

		$parameters->exists('name') && !$this->validName($parameters->get('name')) &&
			$errors->push(new Exceptions\$NAME$NameNotValidException($parameters->get('name')));


		return $errors;
	}

	/**
	 * Validate name
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function validName($name)
	{
		return $name === null || (strlen($name) >= 3 && strlen($name) < 255);
	}

}
