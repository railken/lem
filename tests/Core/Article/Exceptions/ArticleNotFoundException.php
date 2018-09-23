<?php

namespace Railken\Lem\Tests\Core\Article\Exceptions;

class ArticleNotFoundException extends ArticleException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'ARTICLE_NOT_FOUND';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'Not found';
}
