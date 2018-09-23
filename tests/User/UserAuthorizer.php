<?php

namespace Railken\Lem\Tests\User;

use Railken\Lem\Authorizer;
use Railken\Lem\Tokens;

class UserAuthorizer extends Authorizer
{
    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'user.create',
        Tokens::PERMISSION_UPDATE => 'user.update',
        Tokens::PERMISSION_SHOW   => 'user.show',
        Tokens::PERMISSION_REMOVE => 'user.remove',
    ];
}
