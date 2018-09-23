<?php

namespace Railken\Lem;

use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;
use Railken\Lem\Contracts\ValidatorContract;

class Validator implements ValidatorContract
{
    use Concerns\HasManager;
    use Concerns\CallMethods;

    /**
     * Construct.
     *
     * @param ManagerContract $manager
     */
    public function __construct(ManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Bag                                   $parameters
     *
     * @return Collection
     */
    public function validate(EntityContract $entity, Bag $parameters)
    {
        $errors = new Collection();

        $this->callMethods('validate', [$entity, $parameters], function ($return) use (&$errors) {
            $errors = $errors->merge($return);
        });

        return $errors;
    }

    /**
     * Validate uniqueness.
     *
     * @param \Railken\Lem\Contracts\EntityContract $entity
     * @param Bag                                   $parameters
     *
     * @return Collection
     */
    public function validateUniqueness($entity, $parameters)
    {
        $errors = new Collection();

        foreach ($this->getManager()->getUnique() as $name => $attributes) {
            // Check if attribute exists...

            $q = $this->getManager()->getRepository()->getQuery();

            $where = collect();
            foreach ($attributes as $attribute) {
                $attribute = explode(':', $attribute);

                $col = count($attribute) > 1 ? $attribute[1] : $attribute[0];
                $attribute = $attribute[0];

                $value = $parameters->get($attribute, $entity->$attribute);

                if ($value) {
                    $where[$col] = is_object($value) && $value instanceof EntityContract ? $value->id : $value;
                }
            }

            if ($entity->exists) {
                $q->where('id', '!=', $entity->id);
            }

            if ($where->count() > 0 && $q->where($where->toArray())->count() > 0) {
                $exception = $this->getManager()->getException(Tokens::NOT_UNIQUE);

                $errors->push(new $exception($where));
            }
        }

        return $errors;
    }
}
