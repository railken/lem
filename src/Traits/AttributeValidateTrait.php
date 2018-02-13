<?php

namespace Railken\Laravel\Manager\Traits;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tokens;

trait AttributeValidateTrait
{

    /**
     * @var ManagerContract
     */
    protected $manager;
    
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

        $this->required && !$entity->exists && !$parameters->exists($this->name) &&
            $errors->push(new $this->exceptions[Tokens::NOT_DEFINED]($parameters->get($this->name)));

        $this->unique && $parameters->exists($this->name) && $this->isUnique($entity, $parameters->get($this->name)) &&
            $errors->push(new $this->exceptions[Tokens::NOT_UNIQUE]($parameters->get($this->name)));

        $parameters->exists($this->name) &&
            !$this->valid($entity, $parameters->get($this->name)) &&
            $errors->push(new $this->exceptions[Tokens::NOT_VALID]($parameters->get($this->name)));


        return $errors;
    
    }
    
    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed $value
     *
     * @return boolean
     */
    public function isUnique(EntityContract $entity, $value)
    {
        $q = $this->manager->getRepository()->getQuery()->where($this->name, $value);

        $entity->exists && $q->where('id', $entity->id);

        return $q->count() > 0;
    }

    /**
     * Set manager
     *
     * @param ManagerContract $manager
     *
     * @return $this
     */
    public function setManager(ManagerContract $manager)
    {
        $this->manager = $manager;

        return $this;
    }

}
