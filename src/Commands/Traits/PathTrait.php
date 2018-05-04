<?php

namespace Railken\Laravel\Manager\Commands\Traits;

trait PathTrait
{
	public function getAbsolutePathByParameter($path)
	{
        if ($path[0] !== "/") {
            $path = base_path($path);
        }

        return $path;
	}
}