<?php

namespace Railken\Lem\Concerns;

use Illuminate\Support\Collection;
use Railken\Lem\Contracts\SchemaContract;

trait HasSchema
{
    /**
     * @var \Railken\Lem\Contracts\SchemaContract
     */
    public $schema = null;

    /**
     * Set a repository.
     *
     * @param \Railken\Lem\Contracts\SchemaContract $schema
     *
     * @return $this
     */
    public function setSchema(SchemaContract $schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Retrieve the schema.
     *
     * @return \Railken\Lem\Contracts\SchemaContract
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Boot the component.
     *
     * @param array $classes
     */
    public function bootSchema(array $classes)
    {
        $this->setSchema(new $classes['schema']($this));

        $attributes = new Collection();

        foreach ($this->getSchema()->getAttributes() as $attribute) {
            $attributes[$attribute->getName()] = $attribute;
            $attribute->setManager($this);
            $attribute->boot();
        }

        $this->attributes = $attributes;
    }
}
