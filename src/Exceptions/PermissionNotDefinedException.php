<?php

namespace Railken\Laravel\Manager\Exceptions;

use Exception;

class PermissionNotDefinedException extends Exception
{
    public function __construct($class, $code)
    {
        $this->message = sprintf("Missing permission '%s' in \$permissions attribute for %s. Please, check your class", $code, get_class($class));

        parent::__construct();
    }
}
