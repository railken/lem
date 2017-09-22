<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Exceptions\InvalidParamValueException;
use Railken\Laravel\Manager\Exceptions\MissingParamException;
use Railken\Laravel\Manager\Exceptions\ModelByIdNotFoundException;
use Railken\Laravel\Manager\Contracts\AgentContract;

use DB;
use Exception;
use Illuminate\Support\Collection;

abstract class ModelManager
{

    /**
     * @var Railken\Laravel\Manager\ModelRepository
     */
    public $repository;

    /**
     * Construct
     *
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->agent = $agent;
    }

    /**
     * Retrieve agent
     *
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set the agent
     *
     * @param Railken\Laravel\Manager\Contracts\AgentContract $agent
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
     * @param array|ParameterBag $parameters
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
     * @return Railken\Laravel\Manager\RepositoryModel
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Find
     *
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return Railken\Laravel\Manager\Contracts\EntityContract
     */
    public function findOneBy(ParameterBag $parameters)
    {
        if ($this->agent) {
            $parameters = $parameters->filterSearchableByAgent($this->agent);
        }

        $result = $this->getRepository()->findOneBy($parameters->all());

        return $this->agent && $this->authorizer && $this->authorizer->retrieve($result, $parameters)->count() !== 0 ? null : $result;
    }

    /**
     * Find by parameters
     *
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return Collection
     */
    public function findBy(ParameterBag $parameters)
    {
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
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return Railken\Laravel\Manager\ResultAction
     */
    public function create(ParameterBag $parameters)
    {
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
     * @param Railken\Laravel\Manager\Contracts EntityContract
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return Railken\Laravel\Manager\ResultAction
     */
    public function update(EntityContract $entity, ParameterBag $parameters)
    {
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
     * @param Railken\Laravel\Manager\Contracts EntityContract
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return Railken\Laravel\Manager\ResultAction
     */
    public function edit(EntityContract $entity, ParameterBag $parameters)
    {
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
     * @param  Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return Railken\Laravel\Manager\Contracts\EntityContract
     */
    public function save(EntityContract $entity)
    {
        return $entity->save();
    }

    /**
     * Remove a EntityContract
     *
     * @param Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return void
     */
    public function remove(EntityContract $entity)
    {
        $result = new ResultAction();

        if ($this->agent) {
            $parameters = $parameters->filterByAgent($this->agent);
            $result->addErrors($this->authorizer->remove($entity, $parameters));
        }

        return $result->ok() ? $this->delete($entity) : $result;
    }

    /**
     * Delete a EntityContract
     *
     * @param Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return Railken\Laravel\Manager\ResultAction
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
     * @param Railken\Laravel\Manager\Contracts\EntityContract $entity
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return void
     */
    public function fill(EntityContract $entity, ParameterBag $parameters)
    {
        $entity->fill($parameters->filterFill()->all());

        return $entity;
    }

    /**
     * First or create
     *
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return EntityContract
     */
    public function findOrCreate(ParameterBag $parameters)
    {
        $entity = $this->find($parameters);

        return $entity ? $entity : $this->create($this->parameters($parameters));
    }

    /**
     * Update or create
     *
     * @param Railken\Laravel\Manager\ParameterBag $criteria
     * @param Railken\Laravel\Manager\ParameterBag $parameters
     *
     * @return Railken\Laravel\Manager\ResultAction
     */
    public function updateOrCreate(Bag $criteria, ParameterBag $parameters)
    {
        $entity = $this->find($parameters);

        return $entity ? $this->update($entity, $parameters) : $this->create($parameters);
    }
}
