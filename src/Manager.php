<?php

namespace Railken\Lem;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Railken\Bag;
use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;
use Railken\Lem\Contracts\ResultContract;

/**
 * Abstract Manager class.
 */
abstract class Manager implements ManagerContract
{
    use Concerns\HasExceptions;
    use Concerns\HasRepository;
    use Concerns\HasAuthorizer;
    use Concerns\HasSerializer;
    use Concerns\HasValidator;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var Collection
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var array
     */
    protected $unique = [];

    /**
     * @var AgentContract
     */
    protected $agent;

    /**
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ModelNotAuthorizedException::class,
    ];

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->setAgent($agent);
        $this->bootComponents();
        $this->initializeComponents();
        $this->initializeAttributes();
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (preg_match('/OrFail$/', $method)) {
            $method = preg_replace('/OrFail$/', '', $method);

            if (method_exists($this, $method)) {
                $return = $this->$method(...$args);

                if ($return instanceof Result) {
                    if (!$return->ok()) {
                        throw new Exceptions\Exception(sprintf('Something went wrong while interacting with %s, errors: %s', $this->getEntity(), (string) json_encode($return->getSimpleErrors())));
                    } else {
                        return $return;
                    }
                }
            }
        }

        trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()', E_USER_ERROR);
    }

    /**
     * Boot components.
     */
    public function bootComponents()
    {
        // ...
    }

    /**
     * Retrieve new instance of entity.
     *
     * @param array $parameters
     *
     * @return \Railken\Lem\Contracts\EntityContract
     */
    public function newEntity(array $parameters = [])
    {
        $entity = $this->getEntity();

        return new $entity($parameters);
    }

    /**
     * Return entity.
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Initialize attributes.
     */
    public function initializeAttributes()
    {
        $attributes = new Collection();

        foreach ($this->createAttributes() as $attribute) {
            $attributes[$attribute->getName()] = $attribute;
            $attribute->setManager($this);
            $attribute->boot();
        }

        $this->attributes = $attributes;
    }

    /**
     * Initialize components.
     */
    public function initializeComponents()
    {
        if ($this->validator === null) {
            throw new Exceptions\ModelMissingValidatorException($this);
        }

        if ($this->serializer === null) {
            throw new Exceptions\ModelMissingSerializerException($this);
        }

        if ($this->repository === null) {
            throw new Exceptions\ModelMissingRepositoryException($this);
        }

        if ($this->authorizer === null) {
            throw new Exceptions\ModelMissingAuthorizerException($this);
        }

        $this->getRepository()->setEntity($this->getEntity());
    }

    /**
     * Retrieve attributes.
     *
     * @return \Illuminate\Support\Collection
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
     * set agent.
     *
     * @param AgentContract $agent
     *
     * @return $this
     */
    public function setAgent(AgentContract $agent = null)
    {
        if (!$agent) {
            $agent = new Agents\SystemAgent();
        }

        $this->agent = $agent;

        return $this;
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
     * Convert array to Bag.
     *
     * @param mixed $parameters
     *
     * @return Bag
     */
    public function castParameters($parameters)
    {
        return Bag::factory($parameters);
    }

    /**
     * Create a new EntityContract given parameters.
     *
     * @param Bag|array $parameters
     *
     * @return ResultContract
     */
    public function create($parameters)
    {
        return $this->update($this->repository->newEntity(), $parameters, Tokens::PERMISSION_CREATE);
    }

    /**
     * Update a EntityContract given parameters.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Bag|array                             $parameters
     * @param string                                $permission
     *
     * @return ResultContract
     */
    public function update(EntityContract $entity, $parameters, $permission = Tokens::PERMISSION_UPDATE)
    {
        $parameters = $this->castParameters($parameters);

        $result = new Result();

        try {
            DB::beginTransaction();

            $result->addErrors($this->getAuthorizer()->authorize($permission, $entity, $parameters));

            if ($result->ok()) {
                $result->addErrors($this->getValidator()->validate($entity, $parameters));
            }

            if ($result->ok()) {
                $result->addErrors($this->fill($entity, $parameters)->getErrors());
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
     * Fill entity.
     *
     * @param EntityContract $entity
     * @param Bag|array      $parameters
     *
     * @return Result
     */
    public function fill(EntityContract $entity, $parameters)
    {
        $result = new Result();

        foreach ($this->getAttributes() as $attribute) {
            $result->addErrors($attribute->update($entity, $parameters));
        }

        return $result;
    }

    /**
     * Save the entity.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return bool
     */
    public function save(EntityContract $entity)
    {
        return $entity->save();
    }

    /**
     * Remove a EntityContract.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return ResultContract
     */
    public function remove(EntityContract $entity)
    {
        return $this->delete($entity);
    }

    /**
     * Delete a EntityContract.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     *
     * @return ResultContract
     */
    public function delete(EntityContract $entity)
    {
        $result = new Result();

        $result->addErrors($this->authorizer->authorize(Tokens::PERMISSION_REMOVE, $entity, Bag::factory([])));

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
     * @param Bag|array $criteria
     * @param Bag|array $parameters
     *
     * @return ResultContract
     */
    public function findOrCreate($criteria, $parameters = null)
    {
        if ($criteria instanceof Bag) {
            $criteria = $criteria->toArray();
        }

        if ($parameters === null) {
            $parameters = $criteria;
        }

        $parameters = $this->castParameters($parameters);
        $entity = $this->getRepository()->findOneBy($criteria);

        if ($entity == null) {
            return $this->create($parameters);
        }

        $result = new Result();
        $result->getResources()->push($entity);

        return $result;
    }

    /**
     * Update or create.
     *
     * @param Bag|array $criteria
     * @param Bag|array $parameters
     *
     * @return ResultContract
     */
    public function updateOrCreate($criteria, $parameters = null)
    {
        if ($criteria instanceof Bag) {
            $criteria = $criteria->toArray();
        }

        if ($parameters === null) {
            $parameters = $criteria;
        }

        $parameters = $this->castParameters($parameters);
        $entity = $this->getRepository()->findOneBy($criteria);

        return $entity !== null ? $this->update($entity, $parameters) : $this->create($parameters);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return array_values(array_slice(explode('\\', (new \ReflectionClass($this->getRepository()->newEntity()))->getNamespaceName()), -1))[0];
    }

    /**
     * Get Comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Create a new instance of exception.
     *
     * @param string $code
     * @param mixed  $value
     *
     * @return \Exception
     */
    public function newException(string $code, $value): Exception
    {
        $exception = $this->getException($code);

        return new $exception(
            strtoupper(Str::kebab($this->getName())),
            $value
        );
    }

    /**
     * Create attributes.
     *
     * @return array
     */
    protected function createAttributes()
    {
        $r = [];

        foreach ($this->attributes as $attribute) {
            $r[] = $attribute::make()->setManager($this);
        }

        return $r;
    }
}
