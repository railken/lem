<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;
use Respect\Validation\Validator as v;

class TextAttribute extends BaseAttribute
{
    /**
     * Is the attribute fillable.
     *
     * @var bool
     */
    protected $fillable = true;

    /**
     * MinLength.
     *
     * @var int
     */
    protected $minLength = 0;

    /**
     * MaxLength.
     *
     * @var int
     */
    protected $maxLength = 255;

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed          $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return v::length($this->getMinLength(), $this->getMaxLength())->validate($value);
    }

    /**
     * Get min length.
     *
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * Set min length.
     *
     * @param int $minLength
     *
     * @return $this
     */
    public function setMinLength(int $minLength): self
    {
        $this->minLength = $minLength;

        return $this;
    }

    /**
     * Get max length.
     *
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * Set max length.
     *
     * @param int $maxLength
     *
     * @return $this
     */
    public function setMaxLength(int $maxLength): self
    {
        $this->maxLength = $maxLength;

        return $this;
    }
}
