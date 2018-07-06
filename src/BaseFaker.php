<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\FakerContract;

abstract class BaseFaker implements FakerContract
{
    /**
     * Create a new instance.
     *
     * @return static
     */
    public static function make()
    {
        return new static;
    }

    /**
     * @return \Railken\Laravel\Manager\Contracts\EntityContract
     */
    public function entity()
    {
        $manager = new $this->manager;
        $entity = $manager->newEntity();
        $manager->fillOrFail($entity, $this->parameters());
        return $entity;
    }
}
