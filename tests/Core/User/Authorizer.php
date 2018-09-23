<?php

namespace Railken\Lem\Tests\Core\User;

use Railken\Lem\Authorizer as BaseAuthorizer;
use Railken\Lem\Tokens;

class Authorizer extends BaseAuthorizer
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
