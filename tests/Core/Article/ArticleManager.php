<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;
use Railken\Laravel\Manager\Tests\User\UserManager;

class ArticleManager extends ModelManager
{
    /**
     * @var array
     */
    protected static $__components = [];

    /**
     * Construct
     */
    public function __construct()
    {
        $this->author = new UserManager();

        parent::__construct();
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
        $parameters->exists('author') && $entity->author()->associate($parameters->get('author'));

        return parent::fill($entity, $parameters);
    }
}
