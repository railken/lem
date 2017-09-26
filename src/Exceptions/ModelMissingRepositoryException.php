<?php

namespace Railken\Laravel\Manager\Exceptions;

class ModelMissingRepositoryException extends ModelMissingComponentException
{
    protected $component = 'repository';
}
