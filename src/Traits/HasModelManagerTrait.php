<?php

namespace Railken\Laravel\Manager\Traits;

use Railken\Laravel\Manager\Contracts\ManagerContract;

trait HasModelManagerTrait
{
    /**
     * @var ManagerContract
     */
    protected $manager;

    /**
     * Set manager.
     *
     * @param ManagerContract $manager
     *
     * @return $this
     */
    public function setManager(ManagerContract $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager.
     *
     * @return ManagerContract
     */
    public function getManager()
    {
        return $this->manager;
    }
}
