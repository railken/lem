<?php

namespace Railken\Lem\Contracts;

interface FakerContract
{
    /**
     * @return \Railken\Bag
     */
    public function parameters();

    /**
     * @return \Railken\Lem\Contracts\EntityContract
     */
    public function entity();
}
