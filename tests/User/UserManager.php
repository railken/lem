<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Tokens;

class UserManager extends ModelManager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = User::class;
    
    /**
     * List of all attributes.
     *
     * @var array
     */
    protected $attributes = [
        Attributes\Id\IdAttribute::class,
        Attributes\CreatedAt\CreatedAtAttribute::class,
        Attributes\UpdatedAt\UpdatedAtAttribute::class,
        Attributes\Username\UsernameAttribute::class,
        Attributes\Email\EmailAttribute::class,
        Attributes\Password\PasswordAttribute::class,
    ];

    /**
     * List of all exceptions.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\UserNotAuthorizedException::class,
    ];

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
}
