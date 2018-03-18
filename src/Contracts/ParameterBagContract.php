<?php

namespace Railken\Laravel\Manager\Contracts;

interface ParameterBagContract
{
    /**
     * Filter current bag.
     *
     * @return $this
     */
    public function filterWrite();
}
