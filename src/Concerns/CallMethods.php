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
    public function callMethods(string $prefix, array $arguments, Closure $callback)
    {
        $methods = new Collection(get_class_methods($this));

        $methods->filter(function ($method) use ($prefix) {
            return substr($method, 0, strlen($prefix)) === $prefix && $method !== $prefix;
        })->map(function ($method) use ($callback, $arguments) {
            $callback($this->$method(...$arguments));
        });
    }
}
