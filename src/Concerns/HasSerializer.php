<?php

namespace Railken\Lem\Concerns;

use Railken\Lem\Contracts\SerializerContract;

trait HasSerializer
{
    /**
     * @var \Railken\Lem\Contracts\SerializerContract
     */
    public $serializer = null;

    /**
     * Set a repository.
     *
     * @param \Railken\Lem\Contracts\SerializerContract $serializer
     *
     * @return $this
     */
    public function setSerializer(SerializerContract $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Retrieve the serializer.
     *
     * @return \Railken\Lem\Contracts\SerializerContract
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * Boot the component.
     *
     * @param array $classes
     */
    public function bootSerializer(array $classes)
    {
        $this->setSerializer(new $classes['serializer']($this));
    }
}
