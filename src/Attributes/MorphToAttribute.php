<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\BelongsToAttributeContract;
use Railken\Lem\Contracts\EntityContract;

class MorphToAttribute extends BelongsToAttribute implements BelongsToAttributeContract
{
    /**
     * @var array
     */
    protected $relations;

    /**
     * @var string
     */
    protected $relationKey;

    /**
     * Set the key of the relation.
     *
     * @param string $relationKey
     *
     * @return $this
     */
    public function setRelationKey(string $relationKey): self
    {
        $this->relationKey = $relationKey;

        return $this;
    }

    /**
     * Retrieve the key of the relation.
     *
     * @return string
     */
    public function getRelationKey(): string
    {
        return $this->relationKey;
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
     * Set relations.
     *
     * @param array $relations
     */
    public function setRelations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
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
        $key = $entity->{$this->getRelationKey()};

        if (!isset($this->relations[$key])) {
            return false;
        }

        return $value instanceof $key;
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
        $key = $entity->{$this->getRelationKey()};

        $class = $this->relations[$key];

        return new $class($this->getManager()->getAgent());
    }
}
