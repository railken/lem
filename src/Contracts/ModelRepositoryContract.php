<?php

namespace Railken\Laravel\Manager\Contracts;

interface ModelRepositoryContract
{
    /**
     * Retrieve new instance of entity.
     *
     * @param array $parameters
     *
     * @return \Railken\Laravel\Manager\Contracts\EntityContract
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
     * @return \Railken\Laravel\Manager\Contracts\EntityContract
     */
    public function findOneBy($parameters);

    /**
     * Find one by.
     *
     * @param int $id
     *
     * @return \Railken\Laravel\Manager\Contracts\EntityContract
     */
    public function findOneById($id);

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
    
    /**
     * Return query.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQuery();
}
