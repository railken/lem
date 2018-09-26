<?php

namespace Railken\Lem\Concerns;

use Closure;
use Illuminate\Support\Collection;

trait CallMethods
{
    /**
     * @param string  $prefix
     * @param Closure $callback
     */
    public function callMethods(string $prefix, array $arguments, Closure $callback = null)
    {
        $methods = new Collection(get_class_methods($this));

        $methods->filter(function ($method) use ($prefix) {
            return substr($method, 0, strlen($prefix)) === $prefix && $method !== $prefix;
        })->map(function ($method) use ($callback, $arguments) {
            $return = $this->$method(...$arguments);

            if ($callback !== null) {
                $callback($return);
            }
        });
    }
}
