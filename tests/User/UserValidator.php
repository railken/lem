<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelContract;
use Railken\Bag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\User\Exceptions as Exceptions;

class UserValidator
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
	 * Validate 
	 *
	 * @param ModelContract $entity
	 * @param Bag $params
	 * @param bool $required
	 *
	 * @return Collection
	 */
	public function validate(ModelContract $entity, Bag $params, $required = false)
	{
		
		$errors = new Collection();

		if ($required) 
			$errors = $errors->merge($this->validateRequired($params));
		
		$errors = $errors->merge($this->validateValue($entity, $params));

		return $errors;
	}

	/**
	 * Validate "required" values
	 *
	 * @param ModelContract $entity
	 * @param Bag $params
	 *
	 * @return Collection
	 */
	public function validateRequired(Bag $params)
	{
		$errors = new Collection();

		!$params->exists('email') && $errors->push(new Exceptions\UserEmailNotDefinedException($params->get('email')));
		!$params->exists('username') && $errors->push(new Exceptions\UserUsernameNotDefinedException($params->get('username')));
		!$params->exists('password') && $errors->push(new Exceptions\UserPasswordNotDefinedException($params->get('password')));

		return $errors;
	}

	/**
	 * Validate "not valid" values
	 *
	 * @param Bag $params
	 *
	 * @return Collection
	 */
	public function validateValue(ModelContract $entity, Bag $params)
	{
		$errors = new Collection();

		$params->exists('email') && !$this->validEmail($params->get('email')) && 
			$errors->push(new Exceptions\UserEmailNotValidException($params->get('email')));

		$params->exists('email') && !$this->manager->getRepository()->isUniqueEmail($params->get('email'), $entity) && 
			$errors->push(new Exceptions\UserEmailNotUniqueException($params->get('email')));

		$params->exists('username') && !$this->validUsername($params->get('username')) && 
			$errors->push(new Exceptions\UserUsernameNotValidException($params->get('username')));

		$params->exists('password') && !$this->validPassword($params->get('password')) && 
			$errors->push(new Exceptions\UserPasswordNotValidException($params->get('password')));

		$params->exists('role') && !$this->validRole($params->get('role')) &&
			$errors->push(new Exceptions\UserRoleNotValidException($params->get('role')));


		return $errors;
	}

	/**
	 * Validate email
	 *
	 * @param string $email
	 *
	 * @return boolean
	 */
	public function validEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}


	/**
	 * Validate password
	 *
	 * @param string $password
	 *
	 * @return boolean
	 */
	public function validPassword($password)
	{
		return strlen($password) >= 8;
	}

	/**
	 * Validate role
	 *
	 * @param string $role
	 *
	 * @return boolean
	 */
	public function validRole($role)
	{
		return in_array($role, [User::ROLE_USER, User::ROLE_ADMIN]);
	}

	/**
	 * Validate username
	 *
	 * @param string $username
	 *
	 * @return boolean
	 */
	public function validUsername($username)
	{
		return strlen($username) >= 3 && strlen($username) < 32;
	}
}
