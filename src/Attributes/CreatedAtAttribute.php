<?php

namespace Railken\Laravel\Manager\Attributes;

class CreatedAtAttribute extends BaseAttribute
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
}
