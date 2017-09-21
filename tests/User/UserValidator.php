<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\User\Exceptions as Exceptions;

class UserValidator
{

    /**
     * @var ModelManager
     */
    protected $manager;

    /**
     * Construct
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Validate
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     * @param bool $required
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        if (!$entity->exists) {
            $errors = $errors->merge($this->validateRequired($parameters));
        }
        
        $errors = $errors->merge($this->validateValue($entity, $parameters));

        return $errors;
    }

    /**
     * Validate "required" values
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateRequired(ParameterBag $parameters)
    {
        $errors = new Collection();

        !$parameters->exists('email') && $errors->push(new Exceptions\UserEmailNotDefinedException($parameters->get('email')));
        !$parameters->exists('username') && $errors->push(new Exceptions\UserUsernameNotDefinedException($parameters->get('username')));
        !$parameters->exists('password') && $errors->push(new Exceptions\UserPasswordNotDefinedException($parameters->get('password')));

        return $errors;
    }

    /**
     * Validate "not valid" values
     *
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateValue(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $parameters->exists('email') && !$this->validEmail($parameters->get('email')) &&
            $errors->push(new Exceptions\UserEmailNotValidException($parameters->get('email')));

        $parameters->exists('email') && !$this->manager->getRepository()->isUniqueEmail($parameters->get('email'), $entity) &&
            $errors->push(new Exceptions\UserEmailNotUniqueException($parameters->get('email')));

        $parameters->exists('username') && !$this->validUsername($parameters->get('username')) &&
            $errors->push(new Exceptions\UserUsernameNotValidException($parameters->get('username')));

        $parameters->exists('password') && !$this->validPassword($parameters->get('password')) &&
            $errors->push(new Exceptions\UserPasswordNotValidException($parameters->get('password')));

        $parameters->exists('role') && !$this->validRole($parameters->get('role')) &&
            $errors->push(new Exceptions\UserRoleNotValidException($parameters->get('role')));


        return $errors;
    }

    /**
     * Validate email
     *
     * @param string $email
     *
     * @return boolean
     */
    public function validEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    /**
     * Validate password
     *
     * @param string $password
     *
     * @return boolean
     */
    public function validPassword($password)
    {
        return strlen($password) >= 8;
    }

    /**
     * Validate role
     *
     * @param string $role
     *
     * @return boolean
     */
    public function validRole($role)
    {
        return in_array($role, [User::ROLE_USER, User::ROLE_ADMIN]);
    }

    /**
     * Validate username
     *
     * @param string $username
     *
     * @return boolean
     */
    public function validUsername($username)
    {
        return strlen($username) >= 3 && strlen($username) < 32;
    }
}
