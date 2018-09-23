<?php

namespace Railken\Lem\Tests\Core\Foo;

use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Manager as BaseManager;
use Railken\Lem\Tokens;
use Railken\Lem\Attributes;

/**
 * @method Repository getRepository()
 * @method Serializer getSerializer()
 */
class Manager extends BaseManager
{
    /**
     * Describe this manager.
     *
     * @var string
     */
    public $comment = "...";

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
