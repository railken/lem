<?php

namespace Railken\Lem\Exceptions;

class ModelMissingAuthorizerException extends ModelMissingComponentException
{
    protected $component = 'authorizer';
}
