<?php

namespace Railken\Lem\Tests\Core\Foo;

use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Manager as BaseManager;
use Railken\Lem\Tokens;
use Railken\Lem\Attributes;

class Manager extends BaseManager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = Model::class;

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
        $this->setRepository(new Repository);
        $this->setSerializer(new Serializer($this));
        $this->setValidator(new Validator($this));
        $this->setAuthorizer(new Authorizer($this));

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
