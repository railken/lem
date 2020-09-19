<?php

namespace Railken\Lem\Attributes;

class DateAttribute extends DateTimeAttribute
{
    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'date';

    /**
     * Is the attribute fillable.
     *
     * @var bool
     */
    protected $fillable = true;

    /**
     * Format of date
     *
     * @var string
     */
    protected $format = 'Y-m-d';
}
