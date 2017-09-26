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
     * Construct
     *
     * @param AgentContract|null $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->repository = new CommentRepository($this);
        $this->authorizer = new CommentAuthorizer($this);
        $this->validator = new CommentValidator($this);
        $this->serializer = new CommentSerializer($this);

        $this->author = new UserManager();
        $this->article = new ArticleManager();

        parent::__construct($agent);
    }

    /**
     * Filter parameters
     *
     * @param ParameterBag|array $parameters
     *
     * @return ParameterBag
     */
    public function parameters($parameters)
    {
        return new CommentParameterBag($parameters);
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
		$parameters = $this->parameters($parameters);
		$parameters->exists('author') && $entity->author()->associate($parameters->get('author'));
		$parameters->exists('article') && $entity->article()->associate($parameters->get('article'));

		return parent::fill($entity, $parameters);
	}
}
