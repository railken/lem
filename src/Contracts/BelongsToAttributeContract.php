<?php

namespace Railken\Lem\Contracts;

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
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRelationBuilder(EntityContract $entity);

    /**
     * Retrieve relation manager.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return \Railken\Lem\Contracts\ManagerContract
     */
    public function getRelationManager(EntityContract $entity);
}
