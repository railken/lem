<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Exceptions;

class ArticleAuthorNotDefinedException extends ArticleAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'author';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'ARTICLE_AUTHOR_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";

}
