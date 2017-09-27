<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;

class ModelValidator implements ModelValidatorContract
{

    /**
     * @var ModelManager
     */
    protected $manager;

    /**
     * Construct
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $methods = new Collection(get_class_methods($this));


        $methods->filter(function ($method) {
            return substr($method, 0, strlen('validate')) === 'validate' && $method !== 'validate';
        })->map(function ($method) use (&$errors, $entity, $parameters) {
            $errors = $errors->merge($this->$method($entity, $parameters));
        });

        return $errors;
    }
}
