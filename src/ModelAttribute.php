<?php

namespace Railken\Laravel\Manager;

use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Respect\Validation\Validator as v;

abstract class ModelAttribute implements AttributeContract
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
     * @param EntityContract $entity
     * @param ParameterBag   $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $this->required && !$entity->exists && !$parameters->exists($this->name) &&
            $errors->push(new $this->exceptions[Tokens::NOT_DEFINED]($parameters->get($this->name)));

        $this->unique && $parameters->exists($this->name) && $this->isUnique($entity, $parameters->get($this->name)) &&
            $errors->push(new $this->exceptions[Tokens::NOT_UNIQUE]($parameters->get($this->name)));

        $parameters->exists($this->name) &&
            !$this->valid($entity, $parameters->get($this->name)) &&
            $errors->push(new $this->exceptions[Tokens::NOT_VALID]($parameters->get($this->name)));

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
     * Fill entity value.
     *
     * @param EntityContract $entity
     * @param ParameterBag   $parameters
     *
     * @return Collection
     */
    public function fill(EntityContract $entity, ParameterBag $parameters)
    {

        $errors = new Collection();
        $errors = $errors->merge($this->authorize(Tokens::PERMISSION_FILL, $entity, $parameters));
        $errors = $errors->merge($this->validate($entity, $parameters));
        
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
        $q = $this->manager->getRepository()->getQuery()->where($this->name, $value);

        $entity->exists && $q->where('id', $entity->id);

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
}
