<?php

namespace Railken\Lem\Tests\Core\Article;

use Railken\Lem\Attributes;
use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Manager as BaseManager;
use Railken\Lem\Tests\Core\User;

class Manager extends BaseManager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = Model::class;

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->setRepository(new Repository());
        $this->setSerializer(new Serializer($this));
        $this->setValidator(new Validator($this));
        $this->setAuthorizer(new Authorizer($this));

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
                ->setRelationManager(User\Manager::class),
            Attributes\CreatedAtAttribute::make(),
            Attributes\UpdatedAtAttribute::make(),
            Attributes\DeletedAtAttribute::make(),
        ];
    }
}
