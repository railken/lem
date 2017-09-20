<?php

namespace Railken\Laravel\Manager\Tests\User\Exceptions;

use Exception;

use Railken\Laravel\Manager\Tests\User\User;

class UserAttributeException extends Exception
{


	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute;

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_ATTRIBUTE_INVALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is invalid";

	/**
	 * Value of attribute
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Construct
	 *
	 * @param mixed $value
	 */
	public function __construct($value)
	{
		$this->value = $value;

		if (!$this->attribute)
			$this->attribute = get_class($this);
		
		$this->message = sprintf($this->message, $this->attribute, $value);
	}

	/**
	 * Rapresents the exception in the array format
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'code' => $this->getCode(),
			'attribute' => $this->getAttribute(),
			'message' => $this->getMessage(),
			'value' => $this->getValue(),
		];
	}

	/**
	 * Get value of attribute
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Get attribute
	 *
	 * @return string
	 */
	public function getAttribute()
	{
		return $this->attribute;
	}

}