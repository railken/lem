<?php

namespace Railken\Lem\Attributes;

use Railken\Bag;
use Railken\Lem\Contracts\EntityContract;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Railken\Lem\Tokens;

class YamlAttribute extends LongTextAttribute
{
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
        try {
            $value = Yaml::parse($value);
        } catch (ParseException $exception) {
            return false;
        }

        return true;
    }

    /**
     * Validate.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, Bag $parameters)
    {
        $errors = parent::validate($entity, $parameters);


        if ($errors->count() > 0) {
            return $errors;
        }

        $value = (string) $parameters->get($this->name);

        try {
            $value = Yaml::parse($value);
        } catch (ParseException $exception) {
            $errors->push($this->newException(Tokens::NOT_VALID, $value, $exception->getMessage()));
        }

        return $errors;
    }

    /**
     * Update entity value.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param \Railken\Bag                          $parameters
     * @param string $permission
     *
     * @return Collection
     */
    public function update(EntityContract $entity, Bag $parameters, $permission = Tokens::PERMISSION_UPDATE)
    {
        if (is_object($parameters->get($this->name)) || is_array($parameters->get($this->name))) {
            $parameters->set($this->name, Yaml::dump($parameters));
        }

        return parent::update($entity, $parameters, $permission);
    }
}
