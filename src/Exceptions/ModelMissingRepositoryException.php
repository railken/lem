<?php

namespace Railken\Lem\Exceptions;

class ModelMissingRepositoryException extends ModelMissingComponentException
{
    protected $component = 'repository';
}
