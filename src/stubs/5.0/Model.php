<?php

namespace $NAMESPACE$;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

class $NAME$ extends Model implements ModelContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '$LOW:NAME$';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}