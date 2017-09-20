<?php

namespace Railken\Laravel\Manager\Tests\User;

use Laravel\Passport\HasApiTokens;
use Railken\Laravel\Manager\ModelContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements ModelContract
{
    use HasApiTokens, Notifiable, SoftDeletes;

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

    public function setPasswordAttribute($pass)
    {

        $this->attributes['password'] = bcrypt($pass);

    }
}
