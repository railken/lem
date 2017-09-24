<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment\Exceptions;

use Railken\Laravel\Manager\Exceptions\ModelNotAuthorizedExceptionContract;

class CommentNotAuthorizedException extends CommentException implements ModelNotAuthorizedException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'COMMENT_NOT_AUTHORIZED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized";
}
