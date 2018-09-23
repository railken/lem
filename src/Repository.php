<?php

namespace Railken\Lem;

use Railken\Lem\Contracts\ManagerContract;
use Railken\Lem\Contracts\RepositoryContract;

abstract class Repository implements RepositoryContract
{
    use Concerns\HasManager;

    /**
     * Construct.
     *
     * @param \Railken\Lem\Contracts\ManagerContract $manager
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
     * @return \Railken\Lem\Contracts\EntityContract
     */
    public function newEntity(array $parameters = [])
    {
        return $this->getManager()->newEntity($parameters);
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
        return $this->getManager()->getEntity();
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
     * @return \Railken\Lem\Contracts\EntityContract|null|object
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
        $query = $this->newEntity()->newQuery()->select($this->newEntity()->getTable().'.*');

        // $this->getManager()->getAuthorizer()->newQuery($query);

        return $query;
    }
}
