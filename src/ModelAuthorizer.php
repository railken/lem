<?php

namespace Railken\Laravel\Manager;

use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\PolicyContract;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;

class ModelAuthorizer implements ModelAuthorizerContract
{
    use Traits\HasModelManagerTrait;

    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions;

    /**
     * List of all policies.
     *
     * @var array
     */
    protected $policies;

    /**
     * Construct.
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager = null)
    {
        $this->manager = $manager;
    }

    /**
     * @param string         $action
     * @param EntityContract $entity
     * @param ParameterBag   $parameters
     *
     * @return Collection
     */
    public function authorize(string $action, EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $methods = new Collection(get_class_methods($this));

        $methods->filter(function ($method) {
            return substr($method, 0, strlen('authorize')) === 'authorize' && $method !== 'authorize';
        })->map(function ($method) use ($action, &$errors, $entity, $parameters) {
            $errors = $errors->merge($this->$method($action, $entity, $parameters));
        });

        return $errors;
    }

    /**
     * @param string         $action
     * @param EntityContract $entity
     * @param ParameterBag   $parameters
     *
     * @return Collection
     */
    public function authorizeAction(string $action, EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $exception = $this->getManager()->getException(Tokens::NOT_AUTHORIZED);
        $permission = $this->getPermission($action);

        !$this->getManager()->getAgent()->can($permission) && $errors->push(new $exception($permission));

        return $errors;
    }

    /**
     *  Retrieve a permission name given code.
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

    public function getAuthorizedAttributes(string $action, EntityContract $entity)
    {
        return $this->getManager()->getAttributes()->filter(function ($attribute) use ($action, $entity) {
            $errors = $attribute->authorize($action, $entity, []);

            return $errors->count() === 0;
        });
    }
    
    /** 
     * Retrieve permissions
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @return array
     */
    public function getPolicies()
    {
        return $this->policies;
    }

    /**
     * Add a policy
     *
     * @param PolicyContract $policy
     */
    public function addPolicy(PolicyContract $policy)
    {
        $this->policies[] = $policy;
    }

    /**
     * Filter the new query instance with policies
     *
     * @param $query
     */
    public function newQuery($query)
    {
        foreach ($this->policies as $policy) {
            $policy->newQuery($query);
        }
    }
}
