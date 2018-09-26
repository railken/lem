<?php

namespace Railken\Lem\Tests\App\Schemas;

use Railken\Lem\Attributes;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Schema;
use Railken\Lem\Tests\App\Managers\UserManager;

class ArticleSchema extends Schema
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
            Attributes\TextAttribute::make('title')
                ->setUnique(true)
                ->setDefault(function (EntityContract $entity) {
                    return 'a default value';
                }),
            Attributes\TextAttribute::make('description')
                ->setMaxLength(4096),
            Attributes\BelongsToAttribute::make('author_id')
                ->setRelationName('author')
                ->setRelationManager(UserManager::class),
            Attributes\CreatedAtAttribute::make(),
            Attributes\UpdatedAtAttribute::make(),
            Attributes\DeletedAtAttribute::make(),
        ];
    }
}
