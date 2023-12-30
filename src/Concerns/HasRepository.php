<?php

namespace Railken\Lem\Concerns;

use Railken\Lem\Contracts\RepositoryContract;

trait HasRepository
{
    /**
     * @var \Railken\Lem\Contracts\RepositoryContract
     */
    public $repository;

    /**
     * Set a repository.
     *
     * @param \Railken\Lem\Contracts\RepositoryContract $repository
     *
     * @return $this
     */
    public function setRepository(RepositoryContract $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Retrieve a repository.
     *
     * @return \Railken\Lem\Contracts\RepositoryContract
     */
    public function getRepository(): RepositoryContract
    {
        return $this->repository;
    }

    /**
     * Boot the component.
     *
     * @param array $classes
     */
    public function bootRepository(array $classes)
    {
        $this->setRepository(new $classes['repository']());
        $this->getRepository()->setManager($this);
        $this->getRepository()->setEntity($this->getEntity());
    }
}
