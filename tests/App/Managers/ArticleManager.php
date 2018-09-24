<?php

namespace Railken\Lem\Tests\App\Managers;

use Railken\Lem\Attributes;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Manager;

class ArticleManager extends Manager
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
