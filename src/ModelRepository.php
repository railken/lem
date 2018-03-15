<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\ModelRepositoryContract;

abstract class ModelRepository implements ModelRepositoryContract
{

    use Traits\HasModelManagerTrait;

    /**
     * Class entity
     *
     * @var string
     */
    public $entity;

    /**
     * Retrieve new instance of entity
     *
     * @param array $parameters
     *
     * @return entity
     */
    public function newEntity(array $parameters = [])
    {
        $entity = $this->entity;

        return new $entity($parameters);
    }

    /**
     * Return entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Find by primary
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function findBy($parameters)
    {
        return $this->getQuery()->where($parameters)->get();
    }

    /**
     * Find one by
     *
     * @param array $parameters
     *
     * @return Entity
     */
    public function findOneBy($parameters)
    {
        return $this->getQuery()->where($parameters)->first();
    }

    /**
     * Find one by
     *
     * @param integer $id
     *
     * @return Entity
     */
    public function findOneById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }


    /**
     * Find where in
     *
     * @param array $parameters
     *
     * @return Collection
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
     * Return query
     *
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->newEntity()->newQuery();
    }
}
