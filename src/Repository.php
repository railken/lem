<?php

namespace Railken\Lem;

use Railken\Lem\Contracts\RepositoryContract;

class Repository implements RepositoryContract
{
    /**
     * Entity class.
     *
     * @param string $entity
     */
    protected $entity;

    /**
     * Retrieve new instance of entity.
     *
     * @return \Railken\Lem\Contracts\EntityContract
     */
    public function newEntity()
    {
        $entity = $this->getEntity();

        if ($entity == null) {
            throw new Exceptions\RepositoryEntityNotDefinedException();
        }

        return new $entity();
    }

    /**
     * Set entity.
     *
     * @param string $entity
     *
     * @return $this
     */
    public function setEntity(string $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Return entity.
     *
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * Find all.
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAll()
    {
        return $this->getQuery()->get();
    }

    /**
     * Find by primary.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function findBy($parameters = [])
    {
        return $this->getQuery()->where($parameters)->get();
    }

    /**
     * Find one by.
     *
     * @param array $parameters
     *
     * @return \Railken\Lem\Contracts\EntityContract|null|object
     */
    public function findOneBy($parameters = [])
    {
        return $this->getQuery()->where($parameters)->first();
    }

    /**
     * Find one by.
     *
     * @param int $id
     *
     * @return \Railken\Lem\Contracts\EntityContract|null|object
     */
    public function findOneById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Find where in.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function findWhereIn(array $parameters)
    {
        $q = $this->getQuery();

        foreach ($parameters as $name => $value) {
            $q->whereIn($name, $value);
        }

        return $q->get();
    }

    /**
     * Return query.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQuery()
    {
        return $this->newQuery();
    }

    /**
     * Return query.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newQuery()
    {
        return $this->newEntity()->newQuery()->select($this->newEntity()->getTable().'.*');
    }
}
