<?php

namespace Railken\Laravel\Manager\Attributes;

use Railken\Laravel\Manager\Contracts\EntityContract;

class EnumAttribute extends BaseAttribute
{
    /**
     * List of values.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Is a value valid ?
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     * @param mixed                                             $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return in_array($value, $this->getOptions());
    }

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Retrieve options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}