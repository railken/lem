<?php

namespace Railken\Laravel\Manager\Tests\User\Exceptions;

class UserPasswordNotDefinedException extends UserAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'password';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_PASSWORD_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";
	
}