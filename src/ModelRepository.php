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
     * @return \Illuminate\Database\Eloquent\Model
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
     * @return \Illuminate\Support\Collection
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
     * @return \Illuminate\Database\Eloquent\Model
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
     * @return \Illuminate\Database\Eloquent\Model
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
     * Return query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQuery()
    {
        return $this->newQuery();
    }


    /**
     * Return query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newQuery()
    {
        return $this->newEntity()->newQuery();
    }
}
