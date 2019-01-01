<?php

namespace Railken\Lem\Attributes;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Contracts\BelongsToAttributeContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Tokens;

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
     * Retrieve relation manager.
     *
     * @param EntityContract $entity
     *
     * @return \Railken\Lem\Contracts\ManagerContract
     */
    public function getRelationManager(EntityContract $entity)
    {
        $key = $entity->{$this->getRelationKey()};

        if (!isset($this->relations[$key])) {
            throw new \Exception(sprintf("No valid key %s found for relations %s", $key, json_encode($this->relations)));
        }

        $class = $this->relations[$key];

        return new $class($this->getManager()->getAgent());
    }
}
