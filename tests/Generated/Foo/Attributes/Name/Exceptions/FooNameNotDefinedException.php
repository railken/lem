<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo\Attributes\Name\Exceptions;

use Railken\Laravel\Manager\Tests\Generated\Foo\Exceptions\FooAttributeException;

class FooNameNotDefinedException extends FooAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'name';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'FOO_NAME_NOT_DEFINED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is required';
}
