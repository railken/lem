<?php

namespace Railken\Lem\Tests\Generated\Foo;

use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Manager;
use Railken\Lem\Tokens;
use Railken\Lem\Attributes;

class FooManager extends Manager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = Foo::class;

    /**
     * Describe this manager.
     *
     * @var string
     */
    public $comment = "...";

    /**
     * List of all exceptions.
     *
     * @var array
     */
    protected $exceptions = [
        // ...
    ];

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->setRepository(new FooRepository($this));
        $this->setSerializer(new FooSerializer($this));
        $this->setValidator(new FooValidator($this));
        $this->setAuthorizer(new FooAuthorizer($this));

        parent::__construct($agent);
    }
    
    /**
     * List of all attributes.
     *
     * @var array
     */
    protected function createAttributes()
    {
        return [
            Attributes\IdAttribute::make(),
            Attributes\TextAttribute::make('name'),
            Attributes\TextAttribute::make('description')->setMaxLength(4096),
            Attributes\CreatedAtAttribute::make(),
            Attributes\UpdatedAtAttribute::make(),
            Attributes\DeletedAtAttribute::make(),
        ];    
    }
}
