<?php

namespace Railken\Lem\Attributes;

use Illuminate\Database\Eloquent\Relations\Relation;
use Railken\Lem\Contracts\BelongsToAttributeContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;

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

        $classMorphKey = Relation::getMorphedModel($key);

        if ($classMorphKey) {
            $key = $classMorphKey;
        }

        return class_exists($key) && $value instanceof $key;
    }

    /**
     * Retrieve relation manager.
     *
     * @param EntityContract $entity
     *
     * @return \Railken\Lem\Contracts\ManagerContract
     */
    public function getRelationManager(EntityContract $entity = null)
    {
        if (!$entity) {
            return null;
        }

        $key = $entity->{$this->getRelationKey()};

        return $key !== null ? $this->getRelationManagerByKey($key) : null;
    }

    /**
     * Retrieve relation manager by string type.
     *
     * @param string $key
     *
     * @return \Railken\Lem\Contracts\ManagerContract
     */
    public function getRelationManagerByKey(string $key)
    {
        if (!isset($this->relations[$key])) {
            return null;
        }

        $class = $this->relations[$key];

        if ($class instanceof ManagerContract) {
            $manager = clone $class;
            $manager->setAgent($this->getManager()->getAgent());
            return $manager;
        }

        return new $class($this->getManager()->getAgent());
    }

    public function getDescriptor()
    {
        return [
            'relation_key' => $this->getRelationKey(),
        ];
    }
}
