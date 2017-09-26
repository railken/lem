<?php

namespace Railken\Laravel\Manager\Exceptions;

use Exception;

class ModelMissingComponentException extends Exception
{
    public function __construct($manager)
    {
        $this->message = sprintf("Missing component '%s' for %s", $this->component, get_class($manager));
    }
}
