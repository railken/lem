<?php

namespace Railken\Laravel\Manager\Contracts;

interface ModelRepositoryContract
{

    /**
     * Retrieve new instance of entity
     *
     * @param array $parameters
     *
     * @return entity
     */
    public function newEntity(array $parameters = []);

    /**
     * Return entity
     *
     * @return string
     */
    public function getEntity();

    /**
     * Find by primary
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function findBy($parameters);

    /**
     * Find one by
     *
     * @param array $parameters
     *
     * @return Entity
     */
    public function findOneBy($parameters);

    /**
     * Find where in
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function findWhereIn(array $parameters);

    /**
     * Return query
     *
     * @return QueryBuilder
     */
    public function getQuery();
}
