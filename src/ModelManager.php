<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;
use Railken\Laravel\Manager\Agents\SystemAgent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;
use Railken\Laravel\Manager\Exceptions as Exceptions;
use Railken\Laravel\Manager\Contracts\ModelRepositoryContract;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;

abstract class ModelManager implements ManagerContract
{
    /**
     * @var array
     */
    protected static $__components = [];

    /**
     * Construct
     *
     * @param AgentContract|null $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->agent = $agent ? $agent : new SystemAgent();

        foreach (static::$__components[get_class($this)] as $key => $component) {
            class_exists($component) && $this->$key = new $component($this);
        }

        if (!isset($this->authorizer) || !$this->authorizer instanceof ModelAuthorizerContract) {
            throw new Exceptions\ModelMissingAuthorizerException($this);
        }

        if (!isset($this->validator)) {
            throw new Exceptions\ModelMissingValidatorException($this);
        }

        if (!isset($this->serializer)) {
            throw new Exceptions\ModelMissingSerializerException($this);
        }

        if (!isset($this->parameters)) {
            throw new Exceptions\ModelMissingParametersException($this);
        }

        if (!isset($this->repository) || !$this->repository instanceof ModelRepositoryContract) {
            throw new Exceptions\ModelMissingRepositoryException($this);
        }
    }

    /**
     * Set components
     *
     * @param string $key
     * @param array $args
     *
     * @return void
     */
    public static function __callStatic($key, $args)
    {
        static::$__components[static::class][$key] = $args[0];
    }

    /**
     * Convert array to ParameterBag
     *
     * @param mixed $parameters
     *
     * @return ParameterBagContract
     */
    public function castParameters($parameters)
    {
        return $this->parameters::factory($parameters);
    }

    /**
     * Retrieve agent
     *
     * @return AgentContract
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set the agent
     *
     * @param AgentContract $agent
     *
     * @return $this
     */
    public function setAgent(AgentContract $agent)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Retrieve repository
     *
     * @return ModelRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Find
     *
     * @param ParameterBag|mixed $parameters
     *
     * @return EntityContract
     */
    public function findOneBy($parameters)
    {
        $parameters = $this->castParameters($parameters);
        $parameters = $parameters->filterRead($this->agent);

        $result = $this->repository->findOneBy($parameters->all());

        return $result && $this->authorizer->retrieve($result, $parameters)->count() !== 0 ? null : $result;
    }

    /**
     * Find by parameters
     *
     * @param ParameterBag|array $parameters
     *
     * @return Collection
     */
    public function findBy($parameters)
    {
        $parameters = $this->castParameters($parameters);
        $parameters = $parameters->filterRead($this->agent);

        $results = $this->repository->findBy($parameters->all());

        $results = $results->filter(function ($entity, $key) use ($parameters) {
            $this->authorizer->retrieve($entity, $parameters)->count() == 0;
        });

        return $results;
    }

    /**
     * Create a new EntityContract given parameters
     *
     * @param ParameterBag|array $parameters
     *
     * @return ResultAction
     */
    public function create($parameters)
    {
        $result = new ResultAction();
        $entity = $this->repository->newEntity();

        $parameters = $this->castParameters($parameters);

        $parameters = $parameters->filterWrite($this->agent);

        $result->addErrors($this->authorizer->create($entity, $parameters));
        $result->addErrors($this->validator->validate($entity, $parameters));

        return $result->ok() ? $this->edit($entity, $parameters) : $result;
    }

    /**
     * Update a EntityContract given parameters
     *
     * @param EntityContract $entity
     * @param ParameterBag|array $parameters
     *
     * @return ResultAction
     */
    public function update(EntityContract $entity, $parameters)
    {
        $parameters = $this->castParameters($parameters);

        $result = new ResultAction();

        $parameters = $parameters->filterWrite($this->agent);

        $result->addErrors($this->authorizer->update($entity, $parameters));
        $result->addErrors($this->validator->validate($entity, $parameters));

        return $result->ok() ? $this->edit($entity, $parameters) : $result;
    }

    /**
     * Update a EntityContract given parameters
     *
     * @param EntityContract $entity
     * @param ParameterBag|array $parameters
     *
     * @return ResultAction
     */
    public function edit(EntityContract $entity, $parameters)
    {
        $parameters = $this->castParameters($parameters);

        $result = new ResultAction();

        try {
            DB::beginTransaction();

            $this->fill($entity, $parameters);
            $this->save($entity);

            $result->getResources()->push($entity);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $result;
    }

    /**
     * Save the entity
     *
     * @param EntityContract $entity
     *
     * @return EntityContract
     */
    public function save(EntityContract $entity)
    {
        return $entity->save();
    }

    /**
     * Remove a EntityContract
     *
     * @param EntityContract $entity
     *
     * @return void
     */
    public function remove(EntityContract $entity)
    {
        $result = new ResultAction();

        $result->addErrors($this->authorizer->remove($entity, $this->castParameters([])));

        return $result->ok() ? $this->delete($entity) : $result;
    }

    /**
     * Delete a EntityContract
     *
     * @param EntityContract $entity
     *
     * @return ResultAction
     */
    protected function delete(EntityContract $entity)
    {
        $result = new ResultAction();

        try {
            DB::beginTransaction();
            $entity->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $result;
    }


    /**
     * Fill entity EntityContract with array
     *
     * @param EntityContract $entity
     * @param ParameterBagContract $parameters
     *
     * @return void
     */
    public function fill(EntityContract $entity, ParameterBagContract $parameters)
    {
        $parameters = $this->castParameters($parameters);
        $entity->fill($parameters->all());

        return $entity;
    }

    /**
     * First or create
     *
     * @param ParameterBag|array $parameters
     *
     * @return EntityContract
     */
    public function findOrCreate($parameters)
    {
        $parameters = $this->castParameters($parameters);
        $entity = $this->find($parameters);

        return $entity ? $entity : $this->create($this->castParameters($parameters));
    }

    /**
     * Update or create
     *
     * @param ParameterBag|array $criteria
     * @param ParameterBag|array $parameters
     *
     * @return ResultAction
     */
    public function updateOrCreate($criteria, $parameters)
    {
        $criteria = $this->castParameters($criteria);
        $parameters = $this->castParameters($parameters);
        $entity = $this->find($parameters);

        return $entity ? $this->update($entity, $parameters) : $this->create($parameters);
    }
}
