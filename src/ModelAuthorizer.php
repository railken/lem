<?php

namespace Railken\Laravel\Manager;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;

class ModelAuthorizer implements ModelAuthorizerContract
{

    /**
     * @var ModelManager
     */
    protected $manager;

    /**
     * Construct
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     *
     * @param string $action
     * @param EntityContract $entity
     * @param ParameterBag $parameters
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

        foreach ($this->manager->getAttributes() as $attribute) {
            $attribute = new $attribute();
            $attribute->setManager($this->manager);
            $errors = $errors->merge($attribute->authorize(Tokens::PERMISSION_FILL, $entity, $parameters));
        }

        return $errors;
    }


    /**
     *
     * @param string $action
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function authorizeAction(string $action, EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();


        $exception = $this->manager->getException(Tokens::NOT_AUTHORIZED);
        $permission = $this->getPermission($action);        

        !$this->manager->getAgent()->can($permission) && $errors->push(new $exception($permission));

        return $errors;
    }


    /**
     *  Retrieve a permission name given code
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

    
}
