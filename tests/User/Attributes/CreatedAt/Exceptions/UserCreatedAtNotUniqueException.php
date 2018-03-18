<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes\CreatedAt\Exceptions;
use Railken\Laravel\Manager\Tests\User\Exceptions\UserAttributeException;

class UserCreatedAtNotUniqueException extends UserAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'created_at';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_CREATED_AT_NOT_UNIQUE';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is not unique";

}
