<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Illuminate\Support\Collection;

abstract class ModelAttribute implements AttributeContract
{

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model
     *
     * @var boolean
     */
    protected $required = false;

    /**
     * Is the attribute unique 
     *
     * @var boolean
     */
    protected $unique = false;

    /**
     * @var ManagerContract
     */
    protected $manager;

    /**
     * Set manager
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
     * Get manager
     *
     * @return ManagerContract
     */
    public function getManager()
    {
        return $this->manager;
    }
        


    /**
     *  Retrieve a permission name given code
     *
     * @param string $code
     *
     * @return string
     */
    public function getException($code)
    {

        if (!isset($this->exceptions[$code]))
            throw new Exceptions\ExceptionNotDefinedException($this, $code);

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

        if (!isset($this->permissions[$code]))
            throw new Exceptions\PermissionNotDefinedException($this, $code);

        return $this->permissions[$code];
    }

    /**
     * Validate
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
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
     * @param mixed $value
     *
     * @return boolean
     */
    public function isUnique(EntityContract $entity, $value)
    {
        $q = $this->manager->getRepository()->getQuery()->where($this->name, $value);

        $entity->exists && $q->where('id', $entity->id);

        return $q->count() > 0;
    }



    /**
     * Is a value valid ?
     *
     * @param string $action
     * @param EntityContract $entity
     * @param mixed $value
     *
     * @return boolean
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
     * Retrieve name attribute
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}