<?php

namespace Railken\Laravel\Manager\Contracts;

interface ExceptionContract
{
    public function getValue();
    public function getCode();
    public function getLabel();
}
