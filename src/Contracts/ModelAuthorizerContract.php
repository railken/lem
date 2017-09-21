<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Laravel\Manager\ParameterBag;


interface ModelAuthorizerContract
{

    /**
     * Authorize
     *
     * @param string $operation
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function can(string $operation, EntityContract $entity, ParameterBag $parameters);

    /**
     * Authorize create
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function create(EntityContract $entity, ParameterBag $parameters);

    /**
     * Authorize update
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, ParameterBag $parameters);

    /**
     * Authorize retrieve
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function retrieve(EntityContract $entity, ParameterBag $parameters);

    /**
     * Authorize remove
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function remove(EntityContract $entity, ParameterBag $parameters);
}
