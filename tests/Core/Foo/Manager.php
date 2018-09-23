<?php

namespace Railken\Lem\Tests\Core\Foo;

use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Manager as BaseManager;
use Railken\Lem\Tokens;
use Railken\Lem\Attributes;

/**
 * @method \Railken\Lem\Tests\Core\Foo\Repository getRepository()
 * @method \Railken\Lem\Tests\Core\Foo\Validator  getValidator()
 * @method \Railken\Lem\Tests\Core\Foo\Serializer getSerializer()
 * @method \Railken\Lem\Tests\Core\Foo\Authorizer getAuthorizer()
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
