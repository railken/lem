<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\Id\Exceptions;
use Railken\Laravel\Manager\Tests\User\Exceptions\UserAttributeException;

class UserIdNotValidException extends UserAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'id';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_ID_NOT_VALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is not valid";

}
