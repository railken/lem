<?php

namespace $NAMESPACE$\Exceptions;

use Exception;

class $NAME$NotAuthorizedException extends Exception
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = '$NAME$_NOT_AUTHORIZED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized";
}
