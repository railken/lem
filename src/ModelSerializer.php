<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Tokens;
use Illuminate\Support\Collection;

abstract class ModelSerializer implements ModelSerializerContract
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
     * Serialize entity.
     *
     * @param EntityContract $entity
     * @param Collection     $select
     *
     * @return array
     */
    public function serialize(EntityContract $entity, Collection $select = null)
    {
        $bag = new Bag($entity->toArray());

        if ($select) {
            $bag = $bag->only($select->toArray());
        }

        $bag = $bag->only($this->getManager()->authorizer->getAuthorizedAttributes(Tokens::PERMISSION_SHOW, $entity)->keys()->toArray());

        return $bag;
    }
}
