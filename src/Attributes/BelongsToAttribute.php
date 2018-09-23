<?php

namespace Railken\Lem\Attributes;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Contracts\BelongsToAttributeContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Tokens;

class BelongsToAttribute extends BaseAttribute implements BelongsToAttributeContract
{
    /**
     * @var string
     */
    protected $relationName;

    /**
     * @var string
     */
    protected $relationManager;

    /**
     * Set the name of the relation.
     *
     * @param string $relationName
     *
     * @return $this
     */
    public function setRelationName(string $relationName): self
    {
        $this->relationName = $relationName;

        return $this;
    }

    /**
     * Retrieve the name of the relation.
     *
     * @return string
     */
    public function getRelationName(): string
    {
        return $this->relationName;
    }

    /**
     * Retrieve eloquent relation.
     *
     * @param EntityContract $entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRelationBuilder(EntityContract $entity)
    {
        return $entity->{$this->relationName}();
    }

    /**
     * Set relation manager.
     *
     * @param string $relationManager
     */
    public function setRelationManager(string $relationManager): self
    {
        $this->relationManager = $relationManager;

        return $this;
    }

    /**
     * Retrieve relation manager.
     *
     * @param EntityContract $entity
     *
     * @return \Railken\Lem\Contracts\ManagerContract
     */
    public function getRelationManager(EntityContract $entity)
    {
        $class = $this->relationManager;

        return new $class($this->getManager()->getAgent());
    }

    /**
     * Validate.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, Bag $parameters)
    {
        $errors = new Collection();

        $value = $parameters->get($this->getRelationName());

        if ($this->required && !$entity->exists && !$parameters->exists($this->getRelationName())) {
            $errors->push($this->newException(Tokens::NOT_DEFINED)->setValue($parameters->get($this->getName())));
        }

        if ($this->unique && $parameters->exists($this->getRelationName()) && $this->isUnique($entity, $value)) {
            $errors->push($this->newException(Tokens::NOT_UNIQUE)->setValue($parameters->get($this->getName())));
        }

        if ($parameters->exists($this->getRelationName()) && ($value !== null || $this->required) && !$this->valid($entity, $value)) {
            $errors->push($this->newException(Tokens::NOT_VALID)->setValue($parameters->get($this->getName())));
        }

        return $errors;
    }

    /**
     * Is a value valid ?
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param mixed                                 $value
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
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, Bag $parameters)
    {
        $errors = new Collection();

        $m = $this->getRelationManager($entity);

        if (!$parameters->has($this->getRelationName()) && $parameters->exists($this->getName())) {
            $parameters->set($this->getRelationName(), $m->getRepository()->findOneById($parameters->get($this->getName())));
        }

        if ($parameters->has($this->getRelationName())) {
            $val = $parameters->get($this->getRelationName());

            if (is_array($val) || ($val instanceof \stdClass)) {
                $params = json_decode((string) json_encode($val), true);
                $rentity = $entity->{$this->getRelationName()};

                $unique_keys = $m->getAttributes()->filter(function ($attribute) {
                    return $attribute->getUnique();
                })->keys()->toArray();

                $criteria = (new Bag($params))->only($unique_keys);

                $params = $this->filterRelationParameters(new Bag($params));

                if ($entity->exists) {
                    $result = $m->update($rentity, $params);
                } else {
                    $result = $criteria->count() === 0 ? $m->create($params) : $m->findOrCreate($criteria, $params);
                }

                if (!$result->ok()) {
                    $errors = $errors->merge($result->getErrors());

                    return $errors;
                }

                $parameters->set($this->getRelationName(), $result->getResource());
            }
        }

        if (!$parameters->exists($this->getRelationName()) && !$entity->exists) {
            $default = $this->getDefault($entity);

            if ($default !== null) {
                $parameters->set($this->getRelationName(), $default);
            }
        }

        $errors = $errors->merge($this->authorize(Tokens::PERMISSION_FILL, $entity, $parameters));
        $errors = $errors->merge($this->validate($entity, $parameters));

        if ($errors->count() === 0) {
            $errors = $errors->merge($this->fill($entity, $parameters));
        }

        return $errors;
    }

    public function filterRelationParameters(Bag $parameters)
    {
        return $parameters;
    }

    /**
     * Update entity value.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return Collection
     */
    public function fill(EntityContract $entity, Bag $parameters)
    {
        $errors = new Collection();

        if ($parameters->exists($this->getRelationName())) {
            $this->getRelationBuilder($entity)->associate($parameters->get($this->getRelationName()));
        }

        return $errors;
    }
}
