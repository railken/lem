<?php

namespace Railken\Lem\Exceptions;

class ModelMissingSerializerException extends ModelMissingComponentException
{
    protected $component = 'serializer';
}
