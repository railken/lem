<?php

namespace Railken\Laravel\Manager\Tests\User\Exceptions;

use Railken\Laravel\Manager\Exceptions\ModelNotAuthorizedException;

class UserNotAuthorizedException extends ModelNotAuthorizedException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_NOT_AUTHORIZED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized";
	
}