<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;
use Respect\Validation\Validator as v;
use Railken\Lem\Tokens;
use Exception;

class NumberAttribute extends TextAttribute
{
    /**
     * Schema of the attribute
     *
     * @var string
     */
    protected $schema = 'float';

    /**
     * Precision.
     *
     * @var int
     */
    protected $precision = 8;

    /**
     * Scale.
     *
     * @var int
     */
    protected $scale = 2;

    /**
     * Create a new instance of exception.
     *
     * @param string $code
     * @param mixed  $value
     * @param mixed $error
     *
     * @return \Exception
     */
    public function newException(string $code, $value, string $error = null): Exception
    {
        if ($code === Tokens::NOT_VALID) {
            $error = sprintf("The value must be a number, with max %s digits and max %s decimal digits", $this->getPrecision()-$this->getScale(), $this->getScale());
        }

        return parent::newException($code, $value, $error);
    }


    /**
     * Is a value valid ?
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param mixed                                 $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return
            v::numeric()->validate($value) &&
            preg_match(sprintf("/^[-+]?[0-9]{1,%s}(?:\.[0-9]{1,%s})?$/", $this->precision-$this->scale, $this->scale), $value);
    }

    /**
     * Get precision.
     *
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * Set precision.
     *
     * @param int $precision
     *
     * @return $this
     */
    public function setPrecision(int $precision): self
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * Get scale.
     *
     * @return int
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * Set scale.
     *
     * @param int $scale
     *
     * @return $this
     */
    public function setScale(int $scale): self
    {
        $this->scale = $scale;

        return $this;
    }
}
