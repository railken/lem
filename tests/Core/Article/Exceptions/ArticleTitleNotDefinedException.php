<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Exceptions;

class ArticleTitleNotDefinedException extends ArticleAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'title';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'ARTICLE_TITLE_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";

}
