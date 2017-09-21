<?php

namespace Railken\Laravel\Manager\Exceptions;

use Exception;

class ModelNotAuthorizedException extends Exception
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'NOT_AUTHORIZED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized";

}