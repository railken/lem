<?php

namespace Railken\Lem\Exceptions;

use Exception;

class ExceptionNotDefinedException extends Exception
{
    public function __construct($class, $code)
    {
        $this->message = sprintf("Missing exception '%s' in \$exceptions attribute for %s. Please, check your class", $code, get_class($class));

        parent::__construct();
    }
}
