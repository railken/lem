<?php

namespace Railken\Lem;

use Railken\Lem\Contracts\FakerContract;

abstract class Faker implements FakerContract
{
    /**
     * @var \Railken\Lem\Contracts\ManagerContract
     */
    protected $manager;

    /**
     * Create a new instance.
     *
     * @return static
     */
    public static function make()
    {
        return new static();
    }

    /**
     * @return \Railken\Lem\Contracts\EntityContract
     */
    public function entity()
    {
        $manager = new $this->manager();
        $entity = $manager->newEntity();
        $manager->fillOrFail($entity, $this->parameters());

        return $entity;
    }
}
