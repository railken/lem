<?php

namespace Railken\Lem;

use Railken\Lem\Contracts\SchemaContract;

abstract class Schema implements SchemaContract
{
    public function getFillableAttributes()
    {
        return array_filter($this->getAttributes(), function ($attribute) {
            return $attribute->getFillable();
        });
    }

    public function getNameFillableAttributes()
    {
        return array_map(function ($attribute) {
            return $attribute->getName();
        }, $this->getFillableAttributes());
    }
}
