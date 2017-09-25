<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

class ArticleManager extends ModelManager
{

	/**
	 * Construct
	 *
	 * @param AgentContract|null $agent
	 */
	public function __construct(AgentContract $agent = null)
	{
		$this->repository = new ArticleRepository($this);
		$this->authorizer = new ArticleAuthorizer($this);
		$this->validator = new ArticleValidator($this);
		$this->serializer = new ArticleSerializer($this);

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
        return new ArticleParameterBag($parameters);
    }

	/**
	 * Fill the entity
	 *
	 * @param EntityContract $entity
	 * @param ArticleParameterBag|array $parameters
	 *
	 * @return EntityContract
	*/
	public function fill(EntityContract $entity, $parameters)
	{
		$parameters = $parameters->filterFill();


		$parameters->exists('author') && $entity->author()->associate($parameters->get('author'));

		return parent::fill($entity, $parameters);
	}
}
