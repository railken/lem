<?php

namespace Railken\Laravel\Manager;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ModelSerializerContract;

abstract class ModelSerializer implements ModelSerializerContract
{
    use Traits\HasModelManagerTrait;

    /**
     * Construct.
     *
     * @param ManagerContract|null $manager
     */
    public function __construct(ManagerContract $manager = null)
    {
        $this->manager = $manager;
    }

    /**
     * Serialize entity.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     * @param Collection                                        $select
     *
     * @return \Railken\Bag
     */
    public function serialize(EntityContract $entity, Collection $select = null)
    {
        $bag = new Bag($entity->toArray());

        if ($select) {
            $bag = $bag->only($select->toArray());
        }

        $bag = $bag->only($this->getManager()->getAuthorizer()->getAuthorizedAttributes(Tokens::PERMISSION_SHOW, $entity)->keys()->toArray());

        return $bag;
    }
}
