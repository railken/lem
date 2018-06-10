<?php

namespace Railken\Laravel\Manager;

use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;

class ModelValidator implements ModelValidatorContract
{
    use Traits\HasModelManagerTrait;

    /**
     * Construct.
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager = null)
    {
        $this->manager = $manager;
    }

    /**
     * @param EntityContract $entity
     * @param ParameterBag   $parameters
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

    /**
     * Validate uniqueness.
     *
     * @param EntityContract $entity
     * @param ParameterBag   $parameters
     *
     * @return Collection
     */
    public function validateUniqueness($entity, $parameters)
    {
        $errors = new Collection();

        foreach ($this->getManager()->getUnique() as $name => $attributes) {

            // Check if attribute exists...

            $q = $this->getManager()->getRepository()->getQuery();

            $where = collect();
            foreach ($attributes as $attribute) {
                $attribute = explode(':', $attribute);

                $col = count($attribute) > 1 ? $attribute[1] : $attribute[0];
                $attribute = $attribute[0];

                $value = $parameters->get($attribute, $entity->$attribute);

                if ($value) {
                    $where[$col] = is_object($value) ? $value->id : $value;
                }
            }

            $entity->exists && $q->where('id', '!=', $entity->id);

            if ($where->count() > 0 && $q->where($where->toArray())->count() > 0) {
                $class = $this->getManager()->getException(Tokens::NOT_UNIQUE);

                $errors->push(new $class($where));
            }
        }

        return $errors;
    }
}
