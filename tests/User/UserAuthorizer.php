<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelAuthorizer;
use Railken\Laravel\Manager\Tokens;

class UserAuthorizer extends ModelAuthorizer
{
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'user.create',
        Tokens::PERMISSION_UPDATE => 'user.update',
        Tokens::PERMISSION_SHOW => 'user.show',
        Tokens::PERMISSION_REMOVE => 'user.remove',
    ];
}
