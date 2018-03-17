<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;
use Railken\Laravel\Manager\Exceptions as Exceptions;
use Railken\Laravel\Manager\Contracts\ModelRepositoryContract;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;
use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Agents\SystemAgent;

/**
 * Abstract ModelManager class
 *
 */
abstract class ModelManager implements ManagerContract
{
    /**
     * @var array
     */
    protected static $__components = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $unique = [];

    /**
     * @var array
     */
    protected $exceptions = [];


    /**
     * @var AgentContract
     */
    protected $agent;

    /**
     * @var array
     */
    protected $permissions = [];


    /**
     * Construct
     */
    public function __construct(AgentContract $agent = null)
    {
        if (!$agent) {
            $agent = new Agents\SystemAgent();
        }

        $this->agent = $agent;

        $attributes = new Collection();

        foreach ($this->attributes as $attribute) {
            $attribute = new $attribute();
            $attribute->setManager($this);
            $attributes[$attribute->getName()] = $attribute;
        }

        $this->attributes = $attributes;
        
        foreach (static::$__components[get_class($this)] as $key => $component) {
            class_exists($component) && $this->$key = (new $component());
        }

        if (!isset($this->validator) || !$this->validator instanceof ModelValidatorContract) {
            throw new Exceptions\ModelMissingValidatorException($this);
        }

        if (!isset($this->serializer) || !$this->serializer instanceof ModelSerializerContract) {
            throw new Exceptions\ModelMissingSerializerException($this);
        }

        if (!isset($this->parameters) || !$this->parameters instanceof ParameterBagContract) {
            throw new Exceptions\ModelMissingParametersException($this);
        }

        if (!isset($this->repository) || !$this->repository instanceof ModelRepositoryContract) {
            throw new Exceptions\ModelMissingRepositoryException($this);
        }

        if (!isset($this->authorizer) || !$this->authorizer instanceof ModelAuthorizerContract) {
            throw new Exceptions\ModelMissingAuthorizerException($this);
        }

        $this->validator->setManager($this);
        $this->serializer->setManager($this);
        $this->parameters->setManager($this);
        $this->repository->setManager($this);
        $this->authorizer->setManager($this);
    }

    /**
     * Retrieve attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }


    /**
     * Retrieve unique
     *
     * @return array
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * Retrieve an exception class given code
     *
     * @param string $code
     *
     * @return string
     */
    public function getException($code)
    {
        if (!isset($this->exceptions[$code])) {
            throw new Exceptions\ExceptionNotDefinedException($this, $code);
        }

        return $this->exceptions[$code];
    }

    /**
     * Retrieve a permission name given code
     *
     * @param string $code
     *
     * @return string
     */
    public function getPermission($code)
    {
        if (!isset($this->permissions[$code])) {
            throw new Exceptions\PermissionNotDefinedException($this, $code);
        }

        return $this->permissions[$code];
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
     * Get components
     *
     * @param string $key
     *
     * @return string
     */
    public static function getComponent($key)
    {
        return static::$__components[static::class][$key];
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

        $result = $this->repository->findOneBy($parameters->all());

        // Convert to ResultAction
        // Check Permission

        return $result;
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

        $results = $this->repository->findBy($parameters->all());

        // Convert to ResultAction
        // Check Permission

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
        $parameters->filterWrite();


        $result->addErrors($this->authorizer->authorize(Tokens::PERMISSION_CREATE, $entity, $parameters));
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
        $parameters->filterWrite();

        $result = new ResultAction();

        $result->addErrors($this->authorizer->authorize(Tokens::PERMISSION_UPDATE, $entity, $parameters));
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

        $result->addErrors($this->authorizer->authorize(Tokens::PERMISSION_REMOVE, $entity, $this->parameters::factory([])));

        if (!$result->ok()) {
            return $result;
        }

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

        foreach ($this->getAttributes() as $attribute) {
            $attribute->onFill($entity, $parameters);
        }

        $entity->fill($parameters->all());

        return $entity;
    }

    /**
     * First or create
     *
     * @param ParameterBag|array $criteria
     * @param ParameterBag|array $parameters
     *
     * @return EntityContract
     */
    public function findOrCreate($criteria, $parameters)
    {
        $parameters = $this->castParameters($parameters);
        $entity = $this->findOneBy($criteria);

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
        $entity = $this->findOneBy($criteria);

        return $entity ? $this->update($entity, $parameters) : $this->create($parameters);
    }
}
