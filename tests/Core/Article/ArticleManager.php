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
	 * Fill the entity
	 *
	 * @param EntityContract $entity
	 * @param ArticleParameterBag $parameters
	 *
	 * @return EntityContract
	 */
	public function fill(EntityContract $entity, ParameterBag $parameters)
	{
		$parameters = $parameters->filterFill();

		return parent::fill($entity, $parameters);
	}
}
