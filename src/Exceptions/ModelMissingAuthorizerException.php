<?php

namespace Railken\Laravel\Manager\Exceptions;

class ModelMissingAuthorizerException extends ModelMissingComponentException
{
    protected $component = 'authorizer';
}
