<?php

namespace Railken\Lem\Concerns;

use Railken\Lem\Contracts\ValidatorContract;

trait HasValidator
{
    /**
     * @var \Railken\Lem\Contracts\ValidatorContract
     */
    public $validator = null;

    /**
     * @param \Railken\Lem\Contracts\ValidatorContract $validator
     *
     * @return $this
     */
    public function setValidator(ValidatorContract $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @return \Railken\Lem\Contracts\ValidatorContract
     */
    public function getValidator()
    {
        return $this->validator;
    }
}
