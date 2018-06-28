<?php

namespace Railken\Laravel\Manager\Contracts;

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
     * Initialize attributes.
     *
     * @return void
     */
    public function initializeAttributes();

    /**
     * Initialize components.
     *
     * @return void
     */
    public function initializeComponents();

    /**
     * Set a repository.
     *
     * @param \Railken\Laravel\Manager\Contracts\ModelRepositoryContract
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
     * @param \Railken\Laravel\Manager\Contracts\ModelSerializerContract
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
     * @param \Railken\Laravel\Manager\Contracts\ModelAuthorizerContract
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
     * @param \Railken\Laravel\Manager\Contracts\ModelValidatorContract
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
     * @return array
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
     * set agent
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
     * Convert array to ParameterBagContract.
     *
     * @param mixed $parameters
     *
     * @return ParameterBagContract
     */
    public function castParameters($parameters);

    /**
     * Create a new EntityContract given parameters.
     *
     * @param ParameterBagContract|array $parameters
     *
     * @return ResultActionContract
     */
    public function create($parameters);

    /**
     * Update a EntityContract given parameters.
     *
     * @param EntityContract     $entity
     * @param ParameterBagContract|array $parameters
     * @param string             $permission
     *
     * @return ResultActionContract
     */
    public function update(EntityContract $entity, $parameters, $permission = Tokens::PERMISSION_UPDATE);

    /**
     * Save the entity.
     *
     * @param EntityContract $entity
     *
     * @return EntityContract
     */
    public function save(EntityContract $entity);

    /**
     * Remove a EntityContract.
     *
     * @param EntityContract $entity
     *
     * @return void
     */
    public function remove(EntityContract $entity);

    /**
     * Delete a EntityContract.
     *
     * @param EntityContract $entity
     *
     * @return ResultActionContract
     */
    public function delete(EntityContract $entity);

    /**
     * First or create.
     *
     * @param ParameterBagContract|array $criteria
     * @param ParameterBagContract|array $parameters
     *
     * @return EntityContract
     */
    public function findOrCreate($criteria, $parameters = null);

    /**
     * Update or create.
     *
     * @param ParameterBagContract|array $criteria
     * @param ParameterBagContract|array $parameters
     *
     * @return ResultActionContract
     */
    public function updateOrCreate($criteria, $parameters);
}
