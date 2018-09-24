<?php

namespace Railken\Lem\Tests\App\Managers;

use Railken\Lem\Attributes;
use Railken\Lem\Manager;

/**
 * @method \Railken\Lem\Tests\App\Repository getRepository()
 * @method \Railken\Lem\Tests\App\Validator  getValidator()
 * @method \Railken\Lem\Tests\App\Serializer getSerializer()
 * @method \Railken\Lem\Tests\App\Authorizer getAuthorizer()
 */
class FooManager extends Manager
{
    /**
     * Describe this manager.
     *
     * @var string
     */
    public $comment = '...';

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
