<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;

abstract class ModelSerializer implements ModelSerializerContract
{
    /**
     * @var ModelManager
     */
    protected $manager;

    /**
     * Construct
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->manager = $manager;
    }

    

}
