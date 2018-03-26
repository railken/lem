<?php

namespace Railken\Laravel\Manager\Attributes;

use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Contracts\BelongsToAttributeContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;
use Railken\Laravel\Manager\Tokens;

abstract class BelongsToAttribute extends BaseAttribute implements BelongsToAttributeContract
{
    public $relation_manager;

    /**
     * Validate.
     *
     * @param EntityContract       $entity
     * @param ParameterBagContract $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBagContract $parameters)
    {
        $errors = new Collection();

        $this->required && !$entity->exists && !$parameters->exists($this->getRelationName()) &&
            $errors->push(new $this->exceptions[Tokens::NOT_DEFINED]($parameters->get($this->getName())));

        $this->unique && $parameters->exists($this->getRelationName()) && $this->isUnique($entity, $parameters->get($this->getRelationName())) &&
            $errors->push(new $this->exceptions[Tokens::NOT_UNIQUE]($parameters->get($this->getName())));

        $parameters->exists($this->getRelationName()) && !$this->valid($entity, $parameters->get($this->getRelationName())) &&
            $errors->push(new $this->exceptions[Tokens::NOT_VALID]($parameters->get($this->getName())));

        return $errors;
    }

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        $class = $this->getRelationManager($entity)->getRepository()->getEntity();

        return $value instanceof $class;
    }

    /**
     * Update entity value.
     *
     * @param EntityContract       $entity
     * @param ParameterBagContract $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, ParameterBagContract $parameters)
    {
        $errors = new Collection();

        $m = $this->getRelationManager($entity);

        if (!$parameters->has($this->getRelationName()) && $parameters->exists($this->getName())) {
            $parameters->set($this->getRelationName(), $m->getRepository()->findOneById($parameters->get($this->getName())));
        }

        if (!$parameters->exists($this->getRelationName()) && !$entity->exists) {
            $parameters->set($this->getRelationName(), $this->getDefault($entity));
        }


        $errors = $errors->merge($this->authorize(Tokens::PERMISSION_FILL, $entity, $parameters));
        $errors = $errors->merge($this->validate($entity, $parameters));

        if ($errors->count() === 0) {
            $errors = $errors->merge($this->fill($entity, $parameters));
        }

        return $errors;
    }

    /**
     * Update entity value.
     *
     * @param EntityContract       $entity
     * @param ParameterBagContract $parameters
     *
     * @return Collection
     */
    public function fill(EntityContract $entity, ParameterBagContract $parameters)
    {
        $errors = new Collection();

        $parameters->exists($this->getRelationName()) && $this->getRelationBuilder($entity)->associate($parameters->get($this->getRelationName()));

        return $errors;
    }
}
