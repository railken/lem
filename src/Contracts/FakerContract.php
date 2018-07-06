<?php

namespace Railken\Laravel\Manager\Contracts;

interface FakerContract
{
    /**
     * @return \Railken\Bag
     */
    public function parameters();

    /**
     * @return \Railken\Laravel\Manager\Contracts\EntityContract
     */
    public function entity();
}
