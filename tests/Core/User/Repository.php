<?php

namespace Railken\Lem\Tests\Core\User;

use Railken\Lem\Repository as BaseRepository;

class Repository extends BaseRepository
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
