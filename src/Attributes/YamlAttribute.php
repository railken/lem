<?php

namespace Railken\Lem\Attributes;

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
}
