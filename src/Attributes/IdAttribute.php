<?php

namespace Railken\Lem\Attributes;

class IdAttribute extends BaseAttribute
{
    /**
     * Describe this attribute.
     *
     * @var string
     */
    public $comment = 'Indentify the entity';
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'id';
}
