<?php

namespace Railken\Lem\Exceptions;

abstract class ModelMissingComponentException extends Exception
{
    /**
     * @var string
     */
    protected $component;

    public function __construct($manager)
    {
        $this->message = sprintf("Missing component '%s' for %s", $this->component, get_class($manager));

        parent::__construct();
    }
}
