<?php

namespace Railken\Laravel\Manager\Contracts;

interface ModelSerializerContract
{
    
    /**
     * Serialize
     *
     * @param EntityContract $entity
     *
     * @return Collection
     */
    public function serialize(EntityContract $entity);

    /**
     * Serialize
     *
     * @param EntityContract $entity
     *
     * @return Collection
     */
    public function serializeBrief(EntityContract $entity);
}
