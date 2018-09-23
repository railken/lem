<?php

namespace Railken\Lem;

use Railken\Lem\Contracts\FakerContract;

abstract class Faker implements FakerContract
{
    /**
     * Create a new instance.
     *
     * @return static
     */
    public static function make()
    {
        return new static();
    }
}
