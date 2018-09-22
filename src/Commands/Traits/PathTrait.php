<?php

namespace Railken\Laravel\Manager\Commands\Traits;

trait PathTrait
{
    public function getAbsolutePathByParameter($path)
    {
        return getcwd().'/'.$path;
    }
}
