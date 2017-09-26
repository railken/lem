<?php

namespace Railken\Laravel\Manager\Exceptions;

class ModelMissingValidatorException extends ModelMissingComponentException
{
    protected $component = 'validator';
}
