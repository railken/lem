<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Attributes;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Tokens;

class ArticleManager extends ModelManager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = Article::class;

    /**
     * List of all exceptions.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ArticleNotAuthorizedException::class,
    ];

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
