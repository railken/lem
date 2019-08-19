<?php

namespace Railken\Lem\Attributes;

class LongTextAttribute extends TextAttribute
{
    /**
     * MaxLength.
     *
     * @var int
     */
    protected $maxLength = 65535;

    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'text';
}
