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
     * Validate params
     *
     * @param ModelContract $entity
     * @param Bag $params
     *
     * @return Collection
     */
    public function validate(ModelContract $entity, Bag $params)
    {
        return $this->validator->validate($entity, $params);
    }

    /**
     * Validate params
     *
     * @param ModelContract $entity
     * @param Bag $params
     *
     * @return Collection
     */
    public function authorize(ModelContract $entity, Bag $params)
    {
        return $this->getAgent() ? $this->authorizer->authorize($entity, $params) : new Collection();
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
     * @param Bag $params
     *
     * @return ModelContract
     */
    public function findOrCreate(Bag $params)
    {
        $entity = $this->getRepository()->getQuery()->where($params)->first();

        return $entity ? $entity : $this->create($params);
    }

    /**
     * Update or create
     *
     * @param array $criteria
     * @param Bag $params
     *
     * @return ModelContract
     */
    public function updateOrCreate(array $criteria, Bag $params)
    {
        $entity = $this->getRepository()->getQuery()->where($criteria)->first();

        return $entity ? $this->update($entity, $params) : $this->create($params);
    }

    /**
     * Find
     *
     * @param array $params
     *
     * @return mixed
     */
    public function find(array $params)
    {
        return $this->getRepository()->find($params);
    }

    /**
     * Find where in
     *
     * @param array $params
     *
     * @return Collection ?
     */
    public function findWhereIn(array $params)
    {
        return $this->getRepository()->findWhereIn($params);
    }

    /**
     * Create a new ModelContract given array
     *
     * @param Bag $params
     *
     * @return Railken\Laravel\Manager\ModelContract
     */
    public function create(Bag $params)
    {
        $entity = $this->getRepository()->newEntity();
        return $this->update($entity, $params);
    }

    /**
     * Update a ModelContract given array
     *
     * @param Bag $params
     *
     * @return Railken\Laravel\Manager\ModelContract
     */
    public function update(ModelContract $entity, Bag $params)
    {
        DB::beginTransaction();
        $result = new ResultExecute();
        try {

            $params = $this->authorizer->filter($entity, $params);
            $result->addErrors($this->authorize($entity, $params));
            $result->addErrors($this->validate($entity, $params));


            if (!$result->ok()) {
                DB::rollBack();
                return $result;
            }
            
            $this->fill($entity, $params);
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
     * @param Bag $params
     *
     * @return void
     */
    public function fill(ModelContract $entity, Bag $params)
    {
        $entity->fill($params);
        return $entity;
    }

    /**
     * Fill an attribute of relation Many to One given id or entity
     *
     * @param ModelContract $entity
     * @param ModelManager $manager
     * @param Bag $params
     * @param string $attribute
     * @param string $attribute_id
     *
     * @return $entity
     */
    public function fillManyToOneById(ModelContract $entity, ModelManager $manager, $params, $attribute, $attribute_id = null)
    {
        if ($attribute_id == null) {
            $attribute_id = $attribute."_id";
        }

        if (isset($params[$attribute_id])) {
            $value = $manager->getRepository()->findById($params[$attribute_id]);

            if (!$value) {
                throw new ModelByIdNotFoundException($attribute_id, $params[$attribute_id]);
            }

            $params[$attribute] = $value;
        }

        if (isset($params[$attribute])) {
            $value = $params[$attribute];
            $entity->$attribute_id = $params[$attribute]->id;
            $this->vars[$attribute] = $value;
        }

        return $value;
    }

}
