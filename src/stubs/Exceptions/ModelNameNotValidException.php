<?php

namespace $NAMESPACE$\Exceptions;

class UserEmailNotValidException extends $NAME$AttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'NAME';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = '$NAME$_NAME_NOT_VALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is not valid";
	
}