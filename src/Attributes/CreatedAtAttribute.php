<?php

namespace Railken\Lem\Attributes;

class CreatedAtAttribute extends DateTimeAttribute
{
    /**
     * Name of the attribute.
     *
     * @var string
     */
    protected $name = 'created_at';

    /**
     * A short description of the attribute.
     *
     * @var string
     */
    protected $comment = 'Indicate the date when the record was created';

    /**
     * Is the attribute fillable.
     *
     * @var bool
     */
    protected $fillable = false;
}
