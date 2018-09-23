<?php

namespace Railken\Lem\Tests\Core\User;

use Railken\Lem\Attributes;
use Railken\Lem\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * List of all attributes.
     *
     * @var array
     */
    protected function createAttributes()
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
