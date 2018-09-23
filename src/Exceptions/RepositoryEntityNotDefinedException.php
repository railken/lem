<?php

namespace Railken\Lem\Exceptions;

use Exception;

class RepositoryEntityNotDefinedException extends Exception
{
    public function __construct()
    {
        $this->message = sprintf('No entity defined within repository');

        parent::__construct();
    }
}
