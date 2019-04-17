<?php

namespace Railken\Lem\Concerns;

use Closure;

trait Listeners
{
    public static $listeners = [];

    public static function iniListeners(string $name)
    {
        if (!isset(static::$listeners[$name])) {
            static::$listeners[$name] = [];
        }
    }

    public static function listen(string $name, Closure $closure)
    {
        static::iniListeners($name);

        static::$listeners[$name][] = $closure;
    }

    public static function fire(string $name, $data)
    {
        static::iniListeners($name);

        foreach (static::$listeners[$name] as $listener) {
            $listener($data);
        }
    }
}
