<?php

namespace Railken\Lem;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;
use Railken\Lem\Contracts\SerializerContract;

abstract class Serializer implements SerializerContract
{
    use Concerns\HasManager;

    /**
     * Construct.
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Serialize entity.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Collection                            $select
     *
     * @return \Railken\Bag
     */
    public function serialize(EntityContract $entity, Collection $select = null)
    {
        $bag = new Bag($entity->toArray());

        foreach ($this->getManager()->getAttributes() as $attribute) {
            $attribute->pushReadable($entity, $bag);
        }

        if ($select) {
            $bag = $bag->only($select->toArray());
        }
                
        return $bag;
    }
}
