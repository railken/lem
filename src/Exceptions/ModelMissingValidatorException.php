<?php

namespace Railken\Lem\Exceptions;

class ModelMissingValidatorException extends ModelMissingComponentException
{
    protected $component = 'validator';
}
