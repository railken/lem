<?php

namespace $NAMESPACE$;

use Railken\Laravel\Manager\Contracts\EntityContract;

class $NAME$Observer
{
     /**
     * Listen to the User created event.
     *
     * @param  EntityContract  $entity
     * @return void
     */
    public function created(EntityContract $entity)
    {
        // ..
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  EntityContract  $entity
     * @return void
     */
    public function deleting(EntityContract $entity)
    {	
    	// ..
    }
}