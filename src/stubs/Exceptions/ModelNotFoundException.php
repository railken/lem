<?php

namespace $NAMESPACE$\Exceptions;

use Exception;

class $NAMENotFoundException extends Exception
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = '$NAME$_NOT_FOUND';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "Not found";
}
