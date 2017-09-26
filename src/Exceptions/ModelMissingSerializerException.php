<?php

namespace Railken\Laravel\Manager\Exceptions;

class ModelMissingSerializerException extends ModelMissingComponentException
{
    protected $component = 'serializer';
}
