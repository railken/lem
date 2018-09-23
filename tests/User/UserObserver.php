<?php

namespace Railken\Lem\Tests\User;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param User $user
     */
    public function created(User $user)
    {
    }

    /**
     * Listen to the User deleting event.
     *
     * @param User $user
     */
    public function deleting(User $user)
    {
        $user->email = null;
        $user->username = '@deleted';
        $user->save();
    }
}
