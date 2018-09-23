<?php

namespace Railken\Lem\Exceptions;

class ModelMissingParametersException extends ModelMissingComponentException
{
    protected $component = 'parameters';
}
