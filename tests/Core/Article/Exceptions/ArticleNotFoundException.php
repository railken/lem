<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Exceptions;

class $NAMENotFoundException extends ArticleException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'Article_NOT_FOUND';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "Not found";
}
