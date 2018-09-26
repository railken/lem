<?php

namespace Railken\Lem\Tests\App\Schemas;

use Railken\Lem\Attributes;
use Railken\Lem\Schema;

class FooSchema extends Schema
{
    /**
     * Get all the attributes.
     *
     * @var array
     */
    public function getAttributes(): array
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
