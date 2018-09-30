<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;

class ClassNameAttribute extends TextAttribute
{
    /**
     * List of values.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Create a new instance.
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct(string $name = null, array $options = [])
    {
        $this->setOptions($options);
        parent::__construct($name);
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
        foreach ($this->getOptions() as $option) {
            if (class_exists($value) && (new $value()) instanceof $option) {
                return true;
            }
        }

        return false;
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
