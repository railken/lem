<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements EntityContract, UserAgentContract
{
    use Notifiable, SoftDeletes;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Set value for attribute password
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Return if has role user
     *
     * @return bool
     */
    public function isRoleUser()
    {
        return $this->role == static::ROLE_USER;
    }

    /**
     * Return if has role admin
     *
     * @return bool
     */
    public function isRoleAdmin()
    {
        return $this->role == static::ROLE_ADMIN;
    }
}
