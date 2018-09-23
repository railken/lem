<?php

namespace Railken\Lem\Attributes;

/**
 * This attribute will be automatically updated by eloquent.
 */
class UpdatedAtAttribute extends DateTimeAttribute
{
    /**
     * Name of the attribute.
     *
     * @var string
     */
    protected $name = 'updated_at';

    /**
     * A short description of the attribute.
     *
     * @var string
     */
    protected $comment = 'Indicate the date when the record was last updated';
}
