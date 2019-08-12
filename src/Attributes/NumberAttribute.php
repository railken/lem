<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;
use Respect\Validation\Validator as v;

class NumberAttribute extends TextAttribute
{
    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'float';

    /**
     * Is a value valid ?
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param mixed                                 $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return v::numeric()->validate($value);
    }
}
