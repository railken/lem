<?php

namespace Railken\Laravel\Manager\Attributes;

use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;
use Railken\Laravel\Manager\Exceptions as Exceptions;
use Railken\Laravel\Manager\Tokens;
use Respect\Validation\Validator as v;

abstract class BaseAttribute implements AttributeContract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model.
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Is the attribute unique.
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * @var ManagerContract
     */
    protected $manager;

    /**
     * @var array
     */
    protected $exceptions;

    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions;

    /**
     * Constructor.
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->setManager($manager);
    }

    /**
     * Set manager.
     *
     * @param ManagerContract $manager
     *
     * @return $this
     */
    public function setManager(ManagerContract $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager.
     *
     * @return ManagerContract
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     *  Retrieve a permission name given code.
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
     * Is a value valid ?
     *
     * @param string         $action
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function authorize(string $action, EntityContract $entity, $value)
    {
        $errors = new Collection();

        $permission = $this->getPermission($action);
        $exception = $this->getException(Tokens::NOT_AUTHORIZED);

        !$this->getManager()->getAgent()->can($permission) && $errors->push(new $exception($permission));

        return $errors;
    }

    /**
     * Validate.
     *
     * @param EntityContract       $entity
     * @param ParameterBagContract $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBagContract $parameters)
    {
        $errors = new Collection();

        $value = $parameters->get($this->name);

        $this->required && !$entity->exists && !$parameters->exists($this->name) &&
            $errors->push(new $this->exceptions[Tokens::NOT_DEFINED]($value));

        $this->unique && $parameters->exists($this->name) && $this->isUnique($entity, $value) &&
            $errors->push(new $this->exceptions[Tokens::NOT_UNIQUE]($value));

        $parameters->exists($this->name) && !$this->valid($entity, $value) &&
            $errors->push(new $this->exceptions[Tokens::NOT_VALID]($value));

        return $errors;
    }

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return v::length(1, 255)->validate($value);
    }

    /**
     * Update entity value.
     *
     * @param EntityContract       $entity
     * @param ParameterBagContract $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, ParameterBagContract $parameters)
    {
        $errors = new Collection();

        if (!$parameters->has($this->name) && !$entity->exists) {
            $parameters->set($this->name, $this->getDefault($entity));
        }

        $errors = $errors->merge($this->authorize(Tokens::PERMISSION_FILL, $entity, $parameters));
        $errors = $errors->merge($this->validate($entity, $parameters));
        $errors = $errors->merge($this->fill($entity, $parameters));

        return $errors;
    }

    /**
     * Update entity value.
     *
     * @param EntityContract       $entity
     * @param ParameterBagContract $parameters
     *
     * @return Collection
     */
    public function fill(EntityContract $entity, ParameterBagContract $parameters)
    {
        $errors = new Collection();

        $parameters->exists($this->name) && $entity->fill([$this->name => $parameters->get($this->name)]);

        return $errors;
    }

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function isUnique(EntityContract $entity, $value)
    {
        $q = $this->getManager()->getRepository()->getQuery()->where($this->name, $value);

        $entity->exists && $q->where('id', '!=', $entity->id);

        return $q->count() > 0;
    }

    /**
     * Retrieve name attribute.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retrieve default value
     *
     * @param EntityContract $entity
     *
     * @return mixed
     */
    public function getDefault(EntityContract $entity)
    {
        return null;
    }
}
