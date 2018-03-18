<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo\Attributes\CreatedAt\Exceptions;

use Railken\Laravel\Manager\Tests\Generated\Foo\Exceptions\FooAttributeException;

class FooCreatedAtNotValidException extends FooAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'created_at';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'FOO_CREATED_AT_NOT_VALID';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is not valid';
}
