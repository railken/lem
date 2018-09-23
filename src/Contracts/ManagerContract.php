<?php

namespace Railken\Lem\Contracts;

use Railken\Bag;
use Railken\Lem\Tokens;

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
     * @return \Railken\Lem\Contracts\EntityContract
     */
    public function newEntity(array $parameters = []);

    /**
     * Return entity.
     *
     * @return string
     */
    public function getEntity();

    /**
     * Boot manager.
     */
    public function boot();

    /**
     * Set a repository.
     *
     * @param \Railken\Lem\Contracts\RepositoryContract $repository
     *
     * @return $this
     */
    public function setRepository(RepositoryContract $repository);

    /**
     * Retrieve a repository.
     *
     * @return \Railken\Lem\Contracts\RepositoryContract
     */
    public function getRepository();

    /**
     * Set a repository.
     *
     * @param \Railken\Lem\Contracts\SerializerContract $serializer
     *
     * @return $this
     */
    public function setSerializer(SerializerContract $serializer);

    /**
     * Retrieve the serializer.
     *
     * @return \Railken\Lem\Contracts\SerializerContract
     */
    public function getSerializer();

    /**
     * Set a authorizer.
     *
     * @param \Railken\Lem\Contracts\AuthorizerContract $authorizer
     *
     * @return $this
     */
    public function setAuthorizer(AuthorizerContract $authorizer);

    /**
     * Retrieve the authorizer.
     *
     * @return \Railken\Lem\Contracts\AuthorizerContract
     */
    public function getAuthorizer();

    /**
     * @param \Railken\Lem\Contracts\ValidatorContract $validator
     *
     * @return $this
     */
    public function setValidator(ValidatorContract $validator);

    /**
     * @return \Railken\Lem\Contracts\ValidatorContract
     */
    public function getValidator();

    /**
     * Retrieve attributes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAttributes();

    /**
     * Retrieve unique.
     *
     * @return array
     */
    public function getUnique();

    /**
     * Get name.
     *
     * @return string
     */
    public function getName();

    /**
     * Retrieve an exception class given code.
     *
     * @param string $code
     *
     * @return string
     */
    public function getException($code);

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
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return EntityContract
     */
    public function save(EntityContract $entity);

    /**
     * Remove a EntityContract.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     */
    public function remove(EntityContract $entity);

    /**
     * Delete a EntityContract.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
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
