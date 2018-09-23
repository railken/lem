<?php

namespace Railken\Lem\Tests\Core\Article;

use Railken\Lem\Attributes;
use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Manager;
use Railken\Lem\Tests\User\UserManager;

class ArticleManager extends Manager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = Article::class;

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->setRepository(new ArticleRepository($this));
        $this->setSerializer(new ArticleSerializer($this));
        $this->setValidator(new ArticleValidator($this));
        $this->setAuthorizer(new ArticleAuthorizer($this));

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
