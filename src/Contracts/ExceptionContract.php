<?php

namespace Railken\Lem\Contracts;

interface ExceptionContract
{
    public function getValue();

    public function getCode();

    public function getLabel();
}
