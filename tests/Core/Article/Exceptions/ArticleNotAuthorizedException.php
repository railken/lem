<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Exceptions;

class ArticleNotAuthorizedException extends ArticleException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'ARTICLE_NOT_AUTHORIZED';
	
	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized to interact with %s, missing %s permission";
}
