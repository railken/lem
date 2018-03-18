<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo\Attributes\Id\Exceptions;
use Railken\Laravel\Manager\Tests\Generated\Foo\Exceptions\FooAttributeException;

class FooIdNotValidException extends FooAttributeException
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
	protected $code = 'FOO_ID_NOT_VALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is not valid";

}
