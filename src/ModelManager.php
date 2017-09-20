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
     * @var array
     */
    protected $vars = [];

    /**
     * @var queue
     */
    public $queue = [];

    /**
     * @var boolean
     */
    protected $validate_on_execute = true;


    /**
     * Construct
     *
     */
    public function __construct(AgentContract $agent = null)
    {

        $this->agent = $agent;
        $this->vars = collect();
    }

    /**
     * set validate_on_execute
     *
     * @param boolean $validate_on_execute
     *
     * @return $this
     */
    public function validateOnExecute($validate_on_execute)
    {
        $this->validate_on_execute = $validate_on_execute;
    }

    /**
     * get validate on execute
     *
     * @return boolean
     */
    public function toValidate()
    {
        return $this->validate_on_execute;
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
        return $this->toValidate() ? $this->validator->validate($entity, $params) : new Collection();
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
        $result->setErrors($this->validate($entity, $params));
        
        if (!$result->ok())
            return $result;

        try {


            $this->fill($entity, $params);
            $this->save($entity);

            //$result->setErrors($errors);
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

    /**
     * Remove multiple ModelContract
     *
     * @param array $entities
     *
     * @return void
     */
    public function deleteMultiple($entities)
    {
        foreach ($entities as $entity) {
            $this->delete($entity);
        }
    }

    /**
     * Throw an exception if a value is invalid
     *
     * @param string $name
     * @param string $value
     * @param mixed $accepted
     *
     * @return void
     */
    public function throwExceptionInvalidParamValue($name, $value, $accepted)
    {
        if (is_array($accepted)) {
            if (!in_array($value, $accepted)) {
                throw new InvalidParamValueException("Invalid value {$value} for param {$name}. Accepted: ".implode($accepted, ","));
            }
        }
    }

    /**
     * Throw an exception if a parameter is null
     *
     * @param Bag $params
     *
     * @return void
     */
    public function throwExceptionParamsNull($params)
    {
        foreach ($params as $name => $value) {
            if ($value == null) {
                throw new MissingParamException("Missing parameter: {$name}");
            }
        }
    }

    /**
     * Throw an exception if wrong permission
     *
     * @param Bag $params
     *
     * @return void
     */
    public function throwExceptionAccessDenied($permission, $entity)
    {
        if (!$this->can($permission, $entity)) {
            abort(401);
        }
    }

    /**
     * Execute queue
     *
     * @return null
     */
    public function executeQueue()
    {
        foreach ($this->getQueue() as $queue) {
            $queue();
        }

        $this->setQueue([]);
    }
    
    /**
     * Add an operation to queue
     *
     * @param Closure $closure
     *
     * @return this
     */
    public function addQueue(\Closure $closure)
    {
        $this->queue[] = $closure;
    }

    /**
     * Retrieve all queue
     *
     * @return array
     */
    public function getQueue()
    {
        return $this->queue;
    }
    
    /**
     * Add an operation to queue
     *
     * @param array $queue
     *
     * @return array
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    }

}
