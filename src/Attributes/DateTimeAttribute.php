<?php

namespace Railken\Lem\Attributes;

class DateTimeAttribute extends BaseAttribute
{
    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'datetime';

    /**
     * Is the attribute fillable.
     *
     * @var bool
     */
    protected $fillable = true;
}
