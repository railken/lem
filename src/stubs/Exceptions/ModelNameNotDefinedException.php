<?php

namespace $NAMESPACE$\Exceptions;

class $NAME$NameNotDefinedException extends $NAME$AttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'name';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = '$UP:NAME$_NAME_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";
	
}