<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ModelRepositoryContract;

abstract class ModelRepository implements ModelRepositoryContract
{
    use Traits\HasModelManagerTrait;

    /**
     * Class entity.
     *
     * @var string
     */
    public $entity;

    /**
     * Construct.
     *
     * @param \Railken\Laravel\Manager\Contracts\ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->setManager($manager);
    }

    /**
     * Retrieve new instance of entity.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newEntity(array $parameters = [])
    {
        $entity = $this->getEntity();

        return new $entity($parameters);
    }

    /**
     * Return entity.
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Find by primary.
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
     * Find one by.
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
     * Find one by.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
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
        $query = $this->newEntity()->newQuery()->select($this->newEntity()->getTable().".*");

        $this->getManager()->getAuthorizer()->newQuery($query);

        return $query;
    }
}
