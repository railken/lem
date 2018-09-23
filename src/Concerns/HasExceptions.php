<?php

namespace Railken\Lem\Concerns;

use Railken\Lem\Exceptions;

trait HasExceptions
{
    /**
     * Boot exceptions.
     */
    public function bootExceptions()
    {
    }

    /**
     * Retrieve an exception class given code.
     *
     * @param string $code
     *
     * @return string
     */
    public function getException($code)
    {
        if (!isset($this->exceptions[$code])) {
            throw new Exceptions\ExceptionNotDefinedException($this, $code);
        }

        return $this->exceptions[$code];
    }

    /**
     * Retrieve all exceptions.
     *
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
