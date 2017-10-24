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

        foreach ($this->manager->getAttributes() as $attribute) {
            $attribute = new $attribute();
            $attribute->setManager($this->manager);
            $errors = $errors->merge($attribute->validate($entity, $parameters));
        }

        return $errors;
    }
    
    /**
     * Validate uniqueness
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateUniqueness($entity, $parameters)
    {

        $errors = new Collection();

        foreach ($this->manager->getUnique() as $name => $attributes) {

            // Check if attribute exists...

            $q = $this->manager->getRepository()->getQuery();

            $where = collect();
            foreach ($attributes as $attribute) {
                $value = $parameters->get($attribute, $entity->$attribute);

                if ($value)
                    $where[$attribute] = $value;
            }

            $entity->exists && $q->where('id', '!=', $entity->id);

            if ($where->count() > 0 && $entity->where($where->toArray())->count() > 0) {
                $class = $this->manager->getException('not_unique');

                $errors->push(new $class($where));
            }

        }  

        return $errors;
    }
}
