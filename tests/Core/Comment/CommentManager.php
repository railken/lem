<?php

namespace Railken\Laravel\Manager\Tests\Core\Comment;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

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

		parent::__construct($agent);
	}


    /**
     * Filter parameters
     *
     * @param array|ParameterBag $parameters
     *
     * @return ParameterBag
     */
    public function parameters($parameters)
    {
        return new CommentParameterBag($parameters);
    }
}
