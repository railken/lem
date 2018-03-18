<?php

namespace Railken\Laravel\Manager;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;
use Railken\Laravel\Manager\Contracts\ModelRepositoryContract;
use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\ModelValidatorContract;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;

/**
 * Abstract ModelManager class.
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
     * @var \Railken\Laravel\Manager\Contracts\ModelSerializerContract
     */
    public $serializer;

    /**
     * @var \Railken\Laravel\Manager\Contracts\ModelValidatorContract
     */
    public $validator;

    /**
     * @var \Railken\Laravel\Manager\Contracts\ModelRepositoryContract
     */
    public $repository;

    /**
     * @var \Railken\Laravel\Manager\Contracts\ModelAuthorizerContract
     */
    public $authorizer;

    /**
     * Construct.
     */
    public function __construct(AgentContract $agent = null)
    {
        if (!$agent) {
            $agent = new Agents\SystemAgent();
        }

        $this->agent = $agent;

        $this->initializeAttributes();
        $this->initializeComponents();
    }

    /**
     * Initialize attributes.
     *
     * @return void
     */
    public function initializeAttributes()
    {
        $attributes = new Collection();

        foreach ($this->attributes as $attribute) {
            $attribute = new $attribute($this);
            $attributes[$attribute->getName()] = $attribute;
        }

        $this->attributes = $attributes;
    }

    /**
     * Initialize components
     *
     * @return void
     */
    public function initializeComponents()
    {
        if (!$this->validator) {
            throw new Exceptions\ModelMissingValidatorException($this);
        }

        if (!$this->serializer) {
            throw new Exceptions\ModelMissingSerializerException($this);
        }

        if (!$this->repository) {
            throw new Exceptions\ModelMissingRepositoryException($this);
        }

        if (!$this->authorizer) {
            throw new Exceptions\ModelMissingAuthorizerException($this);
        }
    }

    /**
     * Set a repository.
     *
     * @param \Railken\Laravel\Manager\Contracts\ModelRepositoryContract
     *
     * @return $this
     */
    public function setRepository(ModelRepositoryContract $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Retrieve a repository.
     *
     * @return \Railken\Laravel\Manager\Contracts\ModelRepositoryContract
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set a repository.
     *
     * @param \Railken\Laravel\Manager\Contracts\ModelSerializerContract
     *
     * @return $this
     */
    public function setSerializer(ModelSerializerContract $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Retrieve the serializer.
     *
     * @return \Railken\Laravel\Manager\Contracts\ModelSerializerContract
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * Set a authorizer.
     *
     * @param \Railken\Laravel\Manager\Contracts\ModelAuthorizerContract
     *
     * @return $this
     */
    public function setAuthorizer(ModelAuthorizerContract $authorizer)
    {
        $this->authorizer = $authorizer;

        return $this;
    }

    /**
     * Retrieve the authorizer.
     *
     * @return \Railken\Laravel\Manager\Contracts\ModelAuthorizerContract
     */
    public function getAuthorizer()
    {
        return $this->authorizer;
    }

    /**
     * @param \Railken\Laravel\Manager\Contracts\ModelValidatorContract
     *
     * @return $this
     */
    public function setValidator(ModelValidatorContract $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @return \Railken\Laravel\Manager\Contracts\ModelValidatorContract
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Retrieve attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Retrieve unique.
     *
     * @return array
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * Retrieve an exception class given code.
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
     * Retrieve a permission name given code.
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
     * Retrieve agent.
     *
     * @return AgentContract
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set components.
     *
     * @param string $key
     * @param array  $args
     *
     * @return void
     */
    public static function __callStatic($key, $args)
    {
        static::$__components[static::class][$key] = $args[0];
    }

    /**
     * Get components.
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
     * Convert array to ParameterBag.
     *
     * @param mixed $parameters
     *
     * @return ParameterBagContract
     */
    public function castParameters($parameters)
    {
        return ParameterBag::factory($parameters);
    }

    /**
     * Create a new EntityContract given parameters.
     *
     * @param ParameterBag|array $parameters
     *
     * @return ResultAction
     */
    public function create($parameters)
    {
        return $this->update($this->repository->newEntity(), $parameters, Tokens::PERMISSION_CREATE);
    }

    /**
     * Update a EntityContract given parameters.
     *
     * @param EntityContract     $entity
     * @param ParameterBag|array $parameters
     * @param string             $permission
     *
     * @return ResultAction
     */
    public function update(EntityContract $entity, $parameters, $permission = Tokens::PERMISSION_UPDATE)
    {
        $parameters = $this->castParameters($parameters);

        $result = new ResultAction();

        try {
            DB::beginTransaction();

            # Global
            $result->addErrors($this->authorizer->authorize($permission, $entity, $parameters));
            $result->addErrors($this->validator->validate($entity, $parameters));

            # Attributes
            foreach ($this->getAttributes() as $attribute) {
                $result->addErrors($attribute->fill($entity, $parameters));
            }

            if (!$result->ok()) {
                DB::rollBack();
                return $result;
            }

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
     * Save the entity.
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
     * Remove a EntityContract.
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
     * Delete a EntityContract.
     *
     * @param EntityContract $entity
     *
     * @return ResultAction
     */
    protected function delete(EntityContract $entity)
    {
        $result = new ResultAction();

        $result->addErrors($this->authorizer->authorize(Tokens::PERMISSION_REMOVE, $entity, ParameterBag::factory([])));

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
     * First or create.
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
     * Update or create.
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
