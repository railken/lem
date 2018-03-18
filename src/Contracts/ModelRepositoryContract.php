<?php

namespace Railken\Laravel\Manager\Contracts;

interface ModelRepositoryContract
{
    /**
     * Retrieve new instance of entity.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newEntity(array $parameters = []);

    /**
     * Return entity.
     *
     * @return string
     */
    public function getEntity();

    /**
     * Find by primary.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function findBy($parameters);

    /**
     * Find one by.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOneBy($parameters);

    /**
     * Find where in.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function findWhereIn(array $parameters);

    /**
     * Return query.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newQuery();
}
