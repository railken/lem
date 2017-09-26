<?php

namespace Railken\Laravel\Manager\Exceptions;

class ModelMissingParametersException extends ModelMissingComponentException
{
    protected $component = 'parameters';
}
