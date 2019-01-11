<?php

namespace Railken\Lem\Attributes;

use Railken\Bag;
use Railken\Lem\Contracts\EntityContract;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlAttribute extends TextAttribute
{
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
        try {
            $value = Yaml::parse($value);
        } catch (ParseException $exception) {
            return false;
        }

        return true;
    }

    /**
     * Update entity value.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return Collection
     */
    public function update(EntityContract $entity, Bag $parameters)
    {
        if (is_object($parameters->get($this->name)) || is_array($parameters->get($this->name))) {
            $parameters->set($this->name, Yaml::dump($parameters));
        }

        return parent::update($entity, $parameters);
    }
}
