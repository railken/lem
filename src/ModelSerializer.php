<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;

abstract class ModelSerializer implements ModelSerializerContract
{
    use Traits\HasModelManagerTrait;

    /**
     * Construct
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager = null)
    {
        $this->manager = $manager;
    }
}
