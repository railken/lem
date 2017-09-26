<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Tests\Core\Article\ArticleManager;

class CommentManager extends ModelManager
{

    /**
     * @var array
     */
    protected static $__components = [];

    /**
     * Construct
     *
     * @param AgentContract|null $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->author = new UserManager();
        $this->article = new ArticleManager();

        parent::__construct($agent);
    }

    /**
     * Fill the entity
     *
     * @param EntityContract $entity
     * @param ParameterBag|array $parameters
     *
     * @return EntityContract
    */
    public function fill(EntityContract $entity, $parameters)
    {
        $parameters = $this->castParameters($parameters);
        $parameters->exists('author') && $entity->author()->associate($parameters->get('author'));
        $parameters->exists('article') && $entity->article()->associate($parameters->get('article'));

        return parent::fill($entity, $parameters);
    }
}
