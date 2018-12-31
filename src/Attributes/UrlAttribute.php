<?php

namespace Railken\Lem\Attributes;

use Railken\Lem\Contracts\EntityContract;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validation;

class UrlAttribute extends TextAttribute
{
    /**
     * Name of the attribute.
     *
     * @var string
     */
    protected $name = 'email';

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
        $validator = Validation::createValidator();

        $violations = $validator->validate($value, [
            new Url(),
        ]);

        return $violations->count() === 0;
    }
}
