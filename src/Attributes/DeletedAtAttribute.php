<?php

namespace Railken\Laravel\Manager\Attributes;

class DeletedAtAttribute extends DateTimeAttribute
{
    /**
     * Name of the attribute.
     *
     * @var string
     */
    protected $name = 'deleted_at';

    /**
     * A short description of the attribute.
     *
     * @var string
     */
    protected $comment = 'Indicate the date when the record was soft-deleted';
}
