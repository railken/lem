<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

class $NAMENotFoundException extends CommentException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'Comment_NOT_FOUND';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "Not found";
}
