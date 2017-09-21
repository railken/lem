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
     * @var ModelRepository
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
     * @param ParameterBag $parameters
     *
     * @return mixed
     */
    public function find(ParameterBag $parameters)
    {
        return $this->getRepository()->find($parameters->all());
    }

    /**
     * Find where in
     *
     * @param ParameterBag $parameters
     *
     * @return Collection ?
     */
    public function findWhereIn(ParameterBag $parameters)
    {
        return $this->getRepository()->findWhereIn($parameters->all());
    }

    /**
     * First or create
     *
     * @param ParameterBag $parameters
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
     * @param Bag $criteria
     * @param ParameterBag $parameters
     *
     * @return EntityContract
     */
    public function updateOrCreate(Bag $criteria, ParameterBag $parameters)
    {
        $entity = $this->find($parameters);

        return $entity ? $this->update($entity, $parameters) : $this->create($parameters);
    }

    /**
     * Create a new EntityContract given array
     *
     * @param ParameterBag $parameters
     *
     * @return Railken\Laravel\Manager\EntityContract
     */
    public function create(ParameterBag $parameters)
    {
        return $this->update($this->getRepository()->newEntity(), $parameters);
    }

    /**
     * Update a EntityContract given array
     *
     * @param ParameterBag $parameters
     *
     * @return Railken\Laravel\Manager\EntityContract
     */
    public function update(EntityContract $entity, ParameterBag $parameters)
    {
        DB::beginTransaction();
        
        $result = new ResultAction();

        try {
            if ($this->agent) {
                $parameters = $parameters->filterByAgent($this->agent);
                $result->addErrors($this->authorizer->update($entity, $parameters));
            }

            $result->addErrors($this->validator->validate($entity, $parameters));


            if (!$result->ok()) {
                DB::rollBack();
                return $result;
            }
            
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
     * Remove a EntityContract
     *
     * @param Railken\Laravel\Manager\EntityContract $entity
     *
     * @return void
     */
    public function remove(EntityContract $entity)
    {
        return $entity->delete();
    }

    /**
     * Save the entity
     *
     * @param  Railken\Laravel\Manager\EntityContract $entity
     *
     * @return EntityContract
     */
    public function save(EntityContract $entity)
    {
        return $entity->save();
    }

    /**
     * Fill entity EntityContract with array
     *
     * @param Railken\Laravel\Manager\EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return void
     */
    public function fill(EntityContract $entity, ParameterBag $parameters)
    {
        $entity->fill($parameters);

        return $entity;
    }
}
