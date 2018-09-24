<?php

namespace Railken\Lem\Tests\App\Repositories;

use Railken\Lem\Repository;

class UserRepository extends Repository
{
    /**
     * return whatever or not the email is unique.
     *
     * @param string $email
     * @param Model  $user
     *
     * @return bool
     */
    public function isUniqueEmail($email, Model $user)
    {
        return 0 == $this->getQuery()->where('email', $email)->where('id', '!=', $user->id)->count();
    }
}
