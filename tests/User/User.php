<?php

namespace Railken\Laravel\Manager\Tests\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements EntityContract, AgentContract
{
    use Notifiable;
    use SoftDeletes;

    public $permissions = [
        'user.*',
    ];

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
        'username', 'email', 'password',
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
     * Set value for attribute password.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function can($permission, $arguments = [])
    {
        $pp = explode('.', $permission);
        foreach ($this->permissions as $p) {
            if ($permission == $p) {
                return true;
            }
            $p = explode('.', $p);
            foreach ($p as $k => $in) {
                if ('*' == $in) {
                    return true;
                }
                if (!isset($pp[$k])) {
                    break;
                }
                if ($pp[$k] != $in) {
                    break;
                }
            }
        }

        return false;
    }

    public function addPermission($permission)
    {
        $this->permissions[] = $permission;

        return $this;
    }
}
