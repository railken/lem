<?php

namespace Railken\Laravel\Manager\Traits;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;

trait AttributeValidateTrait
{

    /**
     * Validate
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists($this->name) &&
            $errors->push(new $this->exceptions['not_defined']($parameters->get($this->name)));

        $parameters->exists($this->name) &&
            !$this->valid($entity, $parameters->get($this->name)) &&
            $errors->push(new $this->exceptions['not_valid']($parameters->get($this->name)));


        return $errors;
    }

}