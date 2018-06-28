<?php

namespace Railken\Laravel\Manager\Contracts;

interface ParameterBagContract
{
    /**
     * New instance.
     *
     * @param self|array $parameters An array of parameters
     *
     * @return self
     */
    public static function factory($parameters = []);

    /**
     * Dynamically access a parameter.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key);

    /**
     * Dynamically set a value for a a parameter.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __set($key, $value);

    /**
     * Returns the parameters.
     *
     * @return array An array of parameters
     */
    public function all();

    /**
     * Returns the parameters.
     *
     * @return array An array of parameters
     */
    public function toArray();

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys();

    /**
     * Replaces the current parameters by a new set.
     *
     * @param array $parameters An array of parameters
     *
     * @return $this
     */
    public function replace(array $parameters = []);

    /**
     * Adds parameters.
     *
     * @param array $parameters An array of parameters
     *
     * @return $this
     */
    public function add(array $parameters = []);

    /**
     * Returns a parameter by name.
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Sets a parameter by name.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return $this
     */
    public function set($key, $value);

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key);

    /**
     * Alias @has.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function exists($key);

    /**
     * Removes a parameter.
     *
     * @param string $key The key
     *
     * @return $this
     */
    public function remove($key);

    /**
     * Returns the number of parameters.
     *
     * @return int The number of parameters
     */
    public function count();

    /**
     * Get only specific parameters by keys.
     *
     * @param array $keys
     *
     * @return self
     */
    public function only(array $keys);

    /**
     * Filter current bag with specific parameters by keys.
     *
     * @param array $keys
     *
     * @return $this
     */
    public function filter(array $keys);
}
