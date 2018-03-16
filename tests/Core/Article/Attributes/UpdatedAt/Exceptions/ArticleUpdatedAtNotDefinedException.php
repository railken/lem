<?php

namespace Railken\Laravel\Manager\Tests\Core\Article\Attributes\UpdatedAt\Exceptions;
use Railken\Laravel\Manager\Tests\Core\Article\Exceptions\ArticleAttributeException;

class ArticleUpdatedAtNotDefinedException extends ArticleAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'updated_at';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'ARTICLE_UPDATED_AT_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";

}