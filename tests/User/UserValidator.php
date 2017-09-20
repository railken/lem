<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelContract;
use Railken\Bag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\User\Exceptions as Exceptions;

class UserValidator
{

	/**
	 * Construct
	 */
	public function __construct()
	{

	}

	/**
	 * Validate 
	 *
	 * @param Bag $params
	 * @param bool $required
	 *
	 * @return Collection
	 */
	public function validate(Bag $params, $required = false)
	{
		
		$errors = new Collection();

		if ($required) 
			$errors = $errors->merge($this->required($params));
		
		$errors = $errors->merge($this->notValid($params));

		return $errors;
	}

	/**
	 * Validate "required" values
	 *
	 * @param Bag $params
	 *
	 * @return Collection
	 */
	public function required(Bag $params)
	{
		$errors = new Collection();

		!$params->exists('email') && $errors->push(new Exceptions\UserEmailNotValidException($params->get('email')));
		!$params->exists('username') && $errors->push(new Exceptions\UserEmailNotValidException($params->get('password')));
		!$params->exists('email') && $errors->push(new Exceptions\UserEmailNotValidException($params->get('email')));

		return $errors;
	}

	/**
	 * Validate "not valid" values
	 *
	 * @param Bag $params
	 *
	 * @return Collection
	 */
	public function notValid(Bag $params)
	{
		$errors = new Collection();

		if ($params->exists('email') && !$this->validEmail($params->get('email'))) 
			$errors->push(new Exceptions\UserEmailNotValidException($params->get('email')));
		

		if ($params->exists('role') && !$this->validRole($params->get('role')))
			$errors->push(new Exceptions\UserRoleNotValidException($params->get('role')));


		if ($params->exists('username') && !$this->validUsername($params->get('username')))
			$errors->push(new Exceptions\UserUsernameNotValidException($params->get('username')));


		if ($params->exists('password') && !$this->validPassword($params->get('password')))
			$errors->push(new Exceptions\UserPasswordNotValidException($params->get('password')));

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
		return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
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
