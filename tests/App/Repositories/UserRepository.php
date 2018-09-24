<?php

namespace Railken\Lem\Tests\App\Repositories;

use Railken\Lem\Repository;
use Railken\Lem\Tests\App\Models\User;

class UserRepository extends Repository
{
    /**
     * return whatever or not the email is unique.
     *
     * @param string $email
     * @param User   $user
     *
     * @return bool
     */
    public function isUniqueEmail($email, User $user)
    {
        return 0 == $this->getQuery()->where('email', $email)->where('id', '!=', $user->id)->count();
    }
}
