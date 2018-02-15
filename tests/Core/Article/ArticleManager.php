<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Tokens;

class ArticleManager extends ModelManager
{	

    /**
     * List of all attributes
     *
     * @var array
     */
    protected $attributes = [
        Attributes\Title\TitleAttribute::class,
        Attributes\Description\DescriptionAttribute::class,
        Attributes\AuthorId\AuthorIdAttribute::class
    ];

    /**
     * List of all exceptions
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ArticleNotAuthorizedException::class
    ];

	/**
	 * Construct
     *
     * @param AgentContract $agent
     *
	 */
	public function __construct(AgentContract $agent = null)
	{
		parent::__construct($agent);
	}

}
