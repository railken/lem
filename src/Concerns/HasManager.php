<?php

namespace Railken\Lem\Concerns;

use Railken\Lem\Contracts\ManagerContract;

trait HasManager
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
