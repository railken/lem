<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\ModelAuthorizerContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tests\Core\Article\Exceptions as Exceptions;

class ArticleAuthorizer implements ModelAuthorizerContract
{

    /**
     * @var ArticleManager
     */
    protected $manager;

    /**
     * Construct
     */
    public function __construct(ArticleManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Authorize
     *
     * @param string $operation
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function can(string $operation, EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        # SystemAgent can always do anything.
        if ($this->manager->agent instanceof SystemAgentContract) {
            // ...
        }

        # GuestAgent can always do anything.
        if ($this->manager->agent instanceof GuestAgentContract) {
            // ...
        }

        # GuestAgent can always do anything.
        if ($this->manager->agent instanceof UserAgentContract) {
            // ...
            !$this->manager->agent->can($operation, $entity) && $errors->push(new Exceptions\ArticleNotAuthorizedException($entity));
        }
        
        return $errors;
    }

    /**
     * Authorize create
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function create(EntityContract $entity, ParameterBag $parameters)
    {
        return $this->can('create', $entity, $parameters);
    }

    /**
     * Authorize update
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, ParameterBag $parameters)
    {
        return $this->can('update', $entity, $parameters);
    }

    /**
     * Authorize retrieve
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function retrieve(EntityContract $entity, ParameterBag $parameters)
    {
        return $this->can('retrieve', $entity, $parameters);
    }

    /**
     * Authorize remove
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function remove(EntityContract $entity, ParameterBag $parameters)
    {
        return $this->can('remove', $entity, $parameters);
    }
}
