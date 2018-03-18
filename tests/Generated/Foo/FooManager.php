<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Tokens;

class FooManager extends ModelManager
{	

    /**
     * List of all attributes
     *
     * @var array
     */
    protected $attributes = [
        Attributes\Id\IdAttribute::class,
        Attributes\CreatedAt\CreatedAtAttribute::class,
        Attributes\UpdatedAt\UpdatedAtAttribute::class,
        Attributes\DeletedAt\DeletedAtAttribute::class,
        Attributes\Name\NameAttribute::class
    ];

    /**
     * List of all exceptions
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\FooNotAuthorizedException::class
    ];

    /**
     * Construct
     *
     * @param AgentContract $agent
     *
     */
	public function __construct(AgentContract $agent = null)
	{

        $this->setRepository(new FooRepository($this));
        $this->setSerializer(new FooSerializer($this));
        $this->setValidator(new FooValidator($this));
        $this->setAuthorizer(new FooAuthorizer($this));

		parent::__construct($agent);
	}

}
