<?php

namespace Railken\Lem\Contracts;

interface SchemaContract
{
    /**
     * List of all attributes.
     *
     * @var array
     */
    public function getAttributes();
}
