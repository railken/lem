<?php

namespace Railken\Laravel\Manager\Exceptions;

use Exception;

class ModelExceptionNotDefinedException extends Exception
{
    public function __construct($manager, $code)
    {
        $this->message = sprintf("Missing exception '%s' in \$permissions attribute for %s. Please, check your class", $code, get_class($manager));
    }
}
