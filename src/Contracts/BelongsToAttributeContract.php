<?php

namespace Railken\Laravel\Manager\Contracts;

interface BelongsToAttributeContract
{
    /**
     * Retrieve the name of the relation.
     *
     * @return string
     */
    public function getRelationName();

    /**
     * Retrieve eloquent relation.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRelationBuilder(EntityContract $entity);

    /**
     * Retrieve relation manager.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return \Railken\Laravel\Manager\Contracts\ManagerContract
     */
    public function getRelationManager(EntityContract $entity);
}
