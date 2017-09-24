<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Exceptions;

class ArticleNameNotDefinedException extends ArticleAttributeException
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
	protected $code = 'ARTICLE_NAME_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";

}
