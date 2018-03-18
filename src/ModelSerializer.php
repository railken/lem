<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ModelSerializerContract;

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
}
