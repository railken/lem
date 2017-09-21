<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Tests\User\Exceptions as Exceptions;
use Railken\Laravel\Manager\ModelAuthorizerContract;
use Illuminate\Support\Collection;

class UserAuthorizer implements ModelAuthorizerContract
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
     * Authorize
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$this->manager->agent->can('update', $entity) && $errors->push(new Exceptions\UserNotAuthorizedException($entity));

        return $errors;
    }
}
