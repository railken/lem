<?php

namespace Railken\Laravel\Manager;

use Railken\Bag;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\ParameterBagContract;

abstract class ParameterBag extends Bag implements ParameterBagContract
{
    /**
     * Filter current bag
     *
     * @return $this
     */
    public function filterWrite()
    {
        return $this;
    }
    
}
