<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\ModelRepository;

class UserRepository extends ModelRepository
{

    /**
     * Class name entity
     *
     * @var string
     */
    public $entity = User::class;

    /**
     * return whatever or not the email is unique
     *
     * @param string $email
     * @param User $user
     *
     * @return boolean
     */
    public function isUniqueEmail($email, User $user)
    {
        return $this->getQuery()->where('email', $email)->where('id', '!=', $user->id)->count() == 0;
    }
}
