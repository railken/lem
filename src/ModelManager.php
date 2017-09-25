<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Agents\SystemAgent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

abstract class ModelManager
{

    /**
     * @var \Railken\Laravel\Manager\ModelRepository
     */
    public $repository;

    /**
     * Construct
     *
     * @param AgentContract|null $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->agent = $agent ? $agent : new SystemAgent();
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
     * Filter parameters
     *
     * @param ParameterBag|array $parameters
     *
     * @return ParameterBag
     */
    public function parameters($parameters)
    {
        return new ParameterBag($parameters);
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
        $parameters = $this->parameters($parameters);

        if ($this->agent) {
            $parameters = $parameters->filterSearchableByAgent($this->agent);
        }

        $result = $this->getRepository()->findOneBy($parameters->all());

        return $result && $this->agent && $this->authorizer && $this->authorizer->retrieve($result, $parameters)->count() !== 0 ? null : $result;
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
        $parameters = $this->parameters($parameters);

        if ($this->agent) {
            $parameters = $parameters->filterSearchableByAgent($this->agent);
        }

        $results = $this->getRepository()->findBy($parameters->all());

        if ($this->authorizer && $this->agent) {
            $results = $results->filter(function ($entity, $key) use ($parameters) {
                $this->authorizer->retrieve($entity, $parameters)->count() == 0;
            });
        }

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
        $parameters = $this->parameters($parameters);


        $result = new ResultAction();
        $entity = $this->getRepository()->newEntity();

        if ($this->agent) {
            $parameters = $parameters->filterByAgent($this->agent);
            $this->authorizer && $result->addErrors($this->authorizer->create($entity, $parameters));
        }

        $this->validator && $result->addErrors($this->validator->validate($entity, $parameters));

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
        $parameters = $this->parameters($parameters);

        $result = new ResultAction();

        if ($this->agent) {
            $parameters = $parameters->filterByAgent($this->agent);
            $this->authorizer && $result->addErrors($this->authorizer->update($entity, $parameters));
        }

        $this->validator && $result->addErrors($this->validator->validate($entity, $parameters));

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
        $parameters = $this->parameters($parameters);

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

        if ($this->agent) {
            $result->addErrors($this->authorizer->remove($entity, $this->parameters([])));
        }

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
     * @param ParameterBag|array $parameters
     *
     * @return void
     */
    public function fill(EntityContract $entity, $parameters)
    {
        $parameters = $this->parameters($parameters);
        $entity->fill($parameters->filterFill()->all());

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
        $parameters = $this->parameters($parameters);
        $entity = $this->find($parameters);

        return $entity ? $entity : $this->create($this->parameters($parameters));
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
        $criteria = $this->parameters($criteria);
        $parameters = $this->parameters($parameters);
        $entity = $this->find($parameters);

        return $entity ? $this->update($entity, $parameters) : $this->create($parameters);
    }
}
