<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\ModelContract;
use Railken\Laravel\Manager\Exceptions\InvalidParamValueException;
use Railken\Laravel\Manager\Exceptions\MissingParamException;
use Railken\Laravel\Manager\Exceptions\ModelByIdNotFoundException;
use Railken\Laravel\Manager\Permission\AgentContract;

use DB;
use Exception;
use Railken\Bag;
use Illuminate\Support\Collection;

abstract class ModelManager
{
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
     * Has permission to do?
     *
     * @param string $permission
     * @param ModelContract $entity
     *
     * @return bool
     */
    public function can($permission, $entity)
    {
        return $this->getAgent()->can($permission, $entity);
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
     * First or create
     *
     * @param Bag $parameters
     *
     * @return ModelContract
     */
    public function findOrCreate(Bag $parameters)
    {
        $entity = $this->getRepository()->getQuery()->where($parameters)->first();

        return $entity ? $entity : $this->create($parameters);
    }

    /**
     * Update or create
     *
     * @param array $criteria
     * @param Bag $parameters
     *
     * @return ModelContract
     */
    public function updateOrCreate(array $criteria, Bag $parameters)
    {
        $entity = $this->getRepository()->getQuery()->where($criteria)->first();

        return $entity ? $this->update($entity, $parameters) : $this->create($parameters);
    }

    /**
     * Find
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function find(array $parameters)
    {
        return $this->getRepository()->find($parameters);
    }

    /**
     * Find where in
     *
     * @param array $parameters
     *
     * @return Collection ?
     */
    public function findWhereIn(array $parameters)
    {
        return $this->getRepository()->findWhereIn($parameters);
    }

    /**
     * Create a new ModelContract given array
     *
     * @param Bag $parameters
     *
     * @return Railken\Laravel\Manager\ModelContract
     */
    public function create(Bag $parameters)
    {
        return $this->update($this->getRepository()->newEntity(), $parameters);
    }

    /**
     * Update a ModelContract given array
     *
     * @param Bag $parameters
     *
     * @return Railken\Laravel\Manager\ModelContract
     */
    public function update(ModelContract $entity, Bag $parameters)
    {
        DB::beginTransaction();
        $result = new ResultExecute();
        try {

            if ($this->agent) {
                $parameters = $this->authorizer->filter($entity, $parameters);
                $result->addErrors($this->authorizer->authorize($entity, $parameters));
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
     * Remove a ModelContract
     *
     * @param Railken\Laravel\Manager\ModelContract $entity
     *
     * @return void
     */
    public function remove(ModelContract $entity)
    {
        return $entity->delete();
    }

    /**
     * Save the entity
     *
     * @param  Railken\Laravel\Manager\ModelContract $entity
     *
     * @return ModelContract
     */
    public function save(ModelContract $entity)
    {
        return $entity->save();
    }


    /**
     * Fill entity ModelContract with array
     *
     * @param Railken\Laravel\Manager\ModelContract $entity
     * @param Bag $parameters
     *
     * @return void
     */
    public function fill(ModelContract $entity, Bag $parameters)
    {
        $entity->fill($parameters);
        return $entity;
    }
}
