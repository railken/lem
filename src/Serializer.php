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
        $bag = new Bag();

        $bag->set('id', $entity->id);

        foreach ($this->getManager()->getAttributes() as $attribute) {
            $name = $attribute->getName();

            $bag->set($name, $entity->$name);

            //$attribute->pushReadable($entity, $bag);
        }
                
        return $bag;
    }
}
