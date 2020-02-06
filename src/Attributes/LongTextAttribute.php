<?php

namespace Railken\Lem\Attributes;

class LongTextAttribute extends TextAttribute
{
    /**
     * MaxLength.
     *
     * @var int
     */
    protected $maxLength = 4000000;

    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'text';
}
