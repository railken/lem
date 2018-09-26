<?php

namespace Railken\Lem\Tests\App\Schemas;

use Railken\Lem\Attributes;
use Railken\Lem\Schema;

class UserSchema extends Schema
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
            Attributes\TextAttribute::make('username')
                ->setRequired(true)
                ->setMinLength(3),
            Attributes\EmailAttribute::make()
                ->setUnique(true)
                ->setRequired(true),
            Attributes\PasswordAttribute::make()
                ->setRequired(true),
            Attributes\CreatedAtAttribute::make(),
            Attributes\UpdatedAtAttribute::make(),
            Attributes\DeletedAtAttribute::make(),
        ];
    }
}
