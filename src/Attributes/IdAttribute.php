<?php

namespace Railken\Lem\Attributes;

class IdAttribute extends BaseAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'id';

    /**
     * Describe this attribute.
     *
     * @var string
     */
    public $comment = 'Indentify the entity';
}
