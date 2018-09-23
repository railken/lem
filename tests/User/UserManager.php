<?php

namespace Railken\Lem\Tests\User;

use Railken\Lem\Attributes;
use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Manager;

class UserManager extends Manager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = User::class;

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->setRepository(new UserRepository($this));
        $this->setSerializer(new UserSerializer($this));
        $this->setValidator(new UserValidator($this));
        $this->setAuthorizer(new UserAuthorizer($this));

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
