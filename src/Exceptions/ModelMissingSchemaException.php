<?php

namespace Railken\Lem\Exceptions;

class ModelMissingSchemaException extends ModelMissingComponentException
{
    protected $component = 'schema';
}
