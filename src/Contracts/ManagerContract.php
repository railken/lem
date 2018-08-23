<?php

namespace Railken\Laravel\Manager\Contracts;

use Railken\Bag;
use Railken\Laravel\Manager\Tokens;

interface ManagerContract
{
    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null);

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
     * Initialize attributes.
     */
    public function initializeAttributes();

    /**
     * Initialize components.
     */
    public function initializeComponents();

    /**
     * Set a repository.
     *
     * @param \Railken\Laravel\Manager\Contracts\ModelRepositoryContract $repository
     *
     * @return $this
     */
    public function setRepository(ModelRepositoryContract $repository);

    /**
     * Retrieve a repository.
     *
     * @return \Railken\Laravel\Manager\Contracts\ModelRepositoryContract
     */
    public function getRepository();

    /**
     * Set a repository.
     *
     * @param \Railken\Laravel\Manager\Contracts\ModelSerializerContract $serializer
     *
     * @return $this
     */
    public function setSerializer(ModelSerializerContract $serializer);

    /**
     * Retrieve the serializer.
     *
     * @return \Railken\Laravel\Manager\Contracts\ModelSerializerContract
     */
    public function getSerializer();

    /**
     * Set a authorizer.
     *
     * @param \Railken\Laravel\Manager\Contracts\ModelAuthorizerContract $authorizer
     *
     * @return $this
     */
    public function setAuthorizer(ModelAuthorizerContract $authorizer);

    /**
     * Retrieve the authorizer.
     *
     * @return \Railken\Laravel\Manager\Contracts\ModelAuthorizerContract
     */
    public function getAuthorizer();

    /**
     * @param \Railken\Laravel\Manager\Contracts\ModelValidatorContract $validator
     *
     * @return $this
     */
    public function setValidator(ModelValidatorContract $validator);

    /**
     * @return \Railken\Laravel\Manager\Contracts\ModelValidatorContract
     */
    public function getValidator();

    /**
     * Retrieve attributes.
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function getAttributes();

    /**
     * Retrieve unique.
     *
     * @return array
     */
    public function getUnique();

    /**
     * Retrieve an exception class given code.
     *
     * @param string $code
     *
     * @return string
     */
    public function getException($code);

    /**
     * Retrieve a permission name given code.
     *
     * @param string $code
     *
     * @return string
     */
    public function getPermission($code);

    /**
     * set agent.
     *
     * @param AgentContract $agent
     *
     * @return $this
     */
    public function setAgent(AgentContract $agent = null);

    /**
     * Retrieve agent.
     *
     * @return AgentContract
     */
    public function getAgent();

    /**
     * Convert array to Bag.
     *
     * @param mixed $parameters
     *
     * @return Bag
     */
    public function castParameters($parameters);

    /**
     * Create a new EntityContract given parameters.
     *
     * @param Bag|array $parameters
     *
     * @return ResultContract
     */
    public function create($parameters);

    /**
     * Update a EntityContract given parameters.
     *
     * @param EntityContract $entity
     * @param Bag|array      $parameters
     * @param string         $permission
     *
     * @return ResultContract
     */
    public function update(EntityContract $entity, $parameters, $permission = Tokens::PERMISSION_UPDATE);

    /**
     * Save the entity.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return EntityContract
     */
    public function save(EntityContract $entity);

    /**
     * Remove a EntityContract.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     */
    public function remove(EntityContract $entity);

    /**
     * Delete a EntityContract.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return ResultContract
     */
    public function delete(EntityContract $entity);

    /**
     * First or create.
     *
     * @param Bag|array $criteria
     * @param Bag|array $parameters
     *
     * @return ResultContract
     */
    public function findOrCreate($criteria, $parameters = null);

    /**
     * Update or create.
     *
     * @param Bag|array $criteria
     * @param Bag|array $parameters
     *
     * @return ResultContract
     */
    public function updateOrCreate($criteria, $parameters);
}
