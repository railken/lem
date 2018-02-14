<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;
use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Tokens;


class ArticleManager extends ModelManager
{
    /**
     * @var array
     */
    protected static $__components = [];

    /**
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ArticleNotAuthorizedException::class
    ];
    
    /**
     * Construct
     */
    public function __construct(AgentContract $agent)
    {
        parent::__construct($agent);
    }

    /**
     * Fill the entity
     *
     * @param EntityContract $entity
     * @param ParameterBagContract $parameters
     *
     * @return EntityContract
    */
    public function fill(EntityContract $entity, ParameterBagContract $parameters)
    {

        foreach (['author' => 'author'] as $relation => $method)
            $parameters->exists($relation) && $entity->$method()->associate($parameters->get($relation));

        return parent::fill($entity, $parameters);
    }
}
