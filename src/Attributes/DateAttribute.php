<?php

namespace Railken\Lem\Attributes;

class DateAttribute extends BaseAttribute
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
}
